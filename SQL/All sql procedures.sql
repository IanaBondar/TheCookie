
CREATE PROCEDURE RegisterUser(login VARCHAR(64), password VARCHAR(64))
    COMMENT "Регистрация пользователя. Параметры: login, password"
    SQL SECURITY DEFINER 
register:
BEGIN
    
    IF LENGTH(password) < 6 OR password NOT REGEXP '[0-9]' OR password NOT REGEXP '[a-zA-Z]' THEN
        SELECT 'Пароль должен быть длиной не менее 6 символов и содержать как минимум одну букву и одну цифру' AS error;
        LEAVE register;
    END IF;
    INSERT IGNORE INTO Users(login, password) VALUES (login, hashPassword(password));
    IF ROW_COUNT() = 0 THEN
        SELECT 'Такой логин уже занят' AS error;
    ELSE
        CALL LoginUser(login, password);
    END IF;
END register;




CREATE FUNCTION hashPassword(password VARCHAR(50))
    RETURNS VARCHAR(64)
    COMMENT "Хэширование пароля. Параметры: password. Возвращает: 16-ричный хэш длиной 64 символа"
    RETURN SHA2(CONCAT(password, 'megasalt'), 256);





CREATE PROCEDURE LoginUser(lg VARCHAR(50), pw VARCHAR(50))
    COMMENT "Авторизация пользователя. Параметры: login, password"
    SQL SECURITY DEFINER 
BEGIN
    DECLARE tk INT UNSIGNED DEFAULT CEIL(RAND() * 4000000000);
    IF hashPassword(pw) = (SELECT password FROM Users WHERE login = lg)
    THEN
        CALL clearTokens();
        
        INSERT INTO Tokens (login, Token, CreationDate) VALUES (lg, tk, NOW());
        SELECT tk AS token FROM Users WHERE login = lg;
    ELSE
        SELECT 'Пароль или логин неверный' AS error;
    END IF;
END;





CREATE PROCEDURE Logout(tk INT UNSIGNED)
    COMMENT "Выход из аккаунта. Параметры: token"
    SQL SECURITY DEFINER 
BEGIN
    DELETE FROM Tokens WHERE token = tk;
    IF ROW_COUNT() = 0 THEN
        SELECT 'Невалидный токен' AS error;
    ELSE
        SELECT 'Вы успешно вышли из аккаунта' AS message;
    END IF;
END;





CREATE PROCEDURE clearTokens() 
SQL SECURITY INVOKER 
COMMENT 'Очистка старых токенов' 
BEGIN 
    DELETE FROM Tokens WHERE TIMESTAMPDIFF(DAY, creationDate, NOW()) > 7; 
END




CREATE PROCEDURE NewGame(
    IN p_Sec int(11)
)
COMMENT "Создание новой сессии игры. Параметр - количество секунд на ход"
SQL SECURITY DEFINER 
BEGIN
        if p_Sec >= 30 and p_Sec <=90 then
            
            INSERT INTO Sessions (amount, turnTime, startTime)
            VALUES ('6', p_Sec, null);

            SELECT LAST_INSERT_ID() as session_id;
        else select 'Неверные параметры игры' as error;
        end if;
    
END;



CREATE PROCEDURE EnterGame( IN p_sid NVARCHAR(11), IN p_token INT UNSIGNED ) 
COMMENT "Вход в игру, параметры: id игровой сессии, токен"
SQL SECURITY DEFINER 
BEGIN 
    DECLARE p_user_id INT; 
    DECLARE session INT; 
    DECLARE p_login NVARCHAR(11);
    DECLARE count_players INT;
    DECLARE mustbe INT;

    SET p_login = GetLoginByToken(p_token);
    
    SELECT id_session into session from Sessions where id_session=p_sid; 
    if session is null then 
        select 'Игра была закончена или удалена' as error; 
    else 
        SELECT id_user INTO p_user_id FROM Users WHERE login = p_login; 
        IF p_user_id IS NULL THEN select 'Пользователь с таким логином не найден' as error; 
        ELSE
                IF (EXISTS (select 1 from Players where id_session=p_sid and id_user=p_user_id)) then
                    select 'Пользователь уже в игре' as error;
                ELSE 
                SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
                START TRANSACTION;
                    SELECT COUNT(*) INTO count_players FROM Players WHERE id_session = p_sid FOR UPDATE;
                    SELECT CAST(amount AS UNSIGNED)+3 INTO mustbe FROM Sessions WHERE id_session = p_sid FOR UPDATE;
                    if (count_players>=mustbe) then
                        select 'В игре уже достаточное количество игроков' as error;
                    else
                    INSERT INTO Players (id_session, id_user, points, color, hero) VALUES (p_sid, p_user_id, 0, 'Синий', 'Печенька'); 
                COMMIT;
                SELECT turnTime AS seconds from Sessions where id_session=p_sid; END IF;
                end if;
        end if; 
    end if; 
END


CREATE FUNCTION GetLoginByToken(p_token INT UNSIGNED)
RETURNS VARCHAR(11)
BEGIN
    DECLARE v_login VARCHAR(11);

    SELECT login INTO v_login
    FROM Tokens
    WHERE token = p_token;

    RETURN v_login;
END 




CREATE PROCEDURE ExitGame(
    IN p_sid NVARCHAR(11),
    IN p_token INT UNSIGNED
    
)
COMMENT "Выход из игры, параметры: id игровой сессии, токен"
SQL SECURITY DEFINER 
BEGIN
       DECLARE player INT;
        DECLARE p_login NVARCHAR(11);
        

        SET p_login = GetLoginByToken(p_token);
        
            select id_player into player from Players right join Users using(id_user) where id_session=p_sid and login=p_login;
            if player is null then
                select 'Такого игрока нет в игре или игры не существует' as error;
            else
                DELETE from Players where id_player=player;
                if NOT EXISTS (select 1 from Players where id_session=p_sid) then
                    call EndGame(p_sid);
                end if;
                select 1 as success;
            end if;
        
END;




CREATE PROCEDURE ShowAllPlayers(IN p_sid NVARCHAR(11)) 
COMMENT 'Показать логины игроков в сессии, параметры: id игровой сессии' 
SQL SECURITY DEFINER 
BEGIN 
    DECLARE p_users INT;
    DECLARE p_coll INT;
    SET p_users = NULL;
    SET p_coll = NULL;
    SELECT COUNT(login) INTO p_users
    FROM Players
    RIGHT JOIN Users USING (id_user)
    WHERE id_session = p_sid;
    if p_users = 0 THEN
        SELECT 'Игра была закончена или удалена' AS error;
    ELSE
        SELECT CAST(amount AS UNSIGNED) + 3 INTO p_coll FROM Sessions WHERE id_session = p_sid;
        SELECT login AS ARRAY FROM Players
        RIGHT JOIN Users USING(id_user) WHERE id_session = p_sid;
        if p_users = p_coll THEN
            SELECT 1 AS isstarted;
            
            if ((SELECT COUNT(*) FROM SessionsLogs WHERE id_session = p_sid) = 0) THEN
                START TRANSACTION;
                INSERT INTO SessionsLogs (id_session, cards_dealt, seconds_remaining, cards_mustbe)
                VALUES (p_sid, FALSE, 0, 0);
                CALL RandomizePlayers(p_sid);
                CALL AddCardsToInHand(p_sid);
                CALL StartTimer(p_sid);
                COMMIT;
            ELSE
                SELECT "Игра уже началась" AS error;
            END if;
            
        ELSE
            SELECT 0 AS isstarted;
        END if;
    END if;
END


CREATE PROCEDURE FindColor(
    IN p_login NVARCHAR(11),
    IN p_sid NVARCHAR(11)
)
COMMENT "Поиск цвета карт игрока по токену, параметры: логин, id игровой сессии"
SQL SECURITY DEFINER 
BEGIN
    DECLARE player_color NVARCHAR(11);
    set player_color = (select color from Players natural join Users where login=p_login and id_session=p_sid);

    if player_color is null then
        select 'Пользователь или сессия не найдены' as error;
    else
        select player_color as color;
    end if;
    
END;


CREATE PROCEDURE FindColorAndHero(
    IN p_token NVARCHAR(11),
    IN p_sid NVARCHAR(11)
)
COMMENT "Поиск цвета карт игрока и его персонажа по токену, параметры: токен, id игровой сессии"
SQL SECURITY DEFINER 
BEGIN
    DECLARE p_login NVARCHAR(11);
    DECLARE player_color NVARCHAR(11);
    DECLARE player_hero NVARCHAR(11);
    SET p_login = GetLoginByToken(p_token);
    set player_color = (select color from Players natural join Users where login=p_login and id_session=p_sid);
    set player_hero = (select hero from Players natural join Users where login=p_login and id_session=p_sid);

    if (player_color is null) or (player_hero is null) then
        select 'Пользователь или сессия не найдены' as error;
    else
        select player_color as color;
        select player_hero as hero;
    end if;
    
END;

CREATE PROCEDURE RandomizePlayers( IN p_sid NVARCHAR(11) ) 
SQL SECURITY INVOKER 
COMMENT 'INVOKER' 
proc_label:BEGIN 
    DECLARE error_condition INT DEFAULT 0; 
    DECLARE player_count NVARCHAR(11); 
    START TRANSACTION; 
    SET player_count = (SELECT COUNT(*) from Players where id_session=p_sid and color='Синий'); 
    if (player_count = 1) then 
        ROLLBACK; 
        select login from Players natural join Users where id_session=p_sid order by id_player ; 
        LEAVE proc_label; 
    end if; 
    CREATE TEMPORARY TABLE temp_heroes 
    SELECT DISTINCT value FROM Cards 
    where value <> 'Меч' and value <> 'Щит' and value <> 'Холм' ORDER BY RAND() Limit 6; 

    ALTER TABLE temp_heroes ADD COLUMN rn INT AUTO_INCREMENT PRIMARY KEY; 

    CREATE TEMPORARY TABLE temp_players 
    SELECT DISTINCT id_player FROM Players 
    where id_session = p_sid; 
    
    ALTER TABLE temp_players ADD COLUMN rn INT AUTO_INCREMENT PRIMARY KEY; 

    CREATE TEMPORARY TABLE temp_colors 
    SELECT DISTINCT color FROM Cards ORDER BY RAND() Limit 6; 
    
    ALTER TABLE temp_colors ADD COLUMN rn INT AUTO_INCREMENT PRIMARY KEY; 
    
    UPDATE Players p JOIN temp_players tp ON p.id_player = tp.id_player 
    JOIN temp_heroes th ON th.rn = tp.rn 
    JOIN temp_colors tc ON tc.rn = tp.rn 
    SET p.hero = th.value, p.color = tc.color 
    where id_session=p_sid; 
    
    drop table temp_heroes; 
    drop table temp_players; 
    drop table temp_colors; 
    
    
    COMMIT; 
END

CREATE PROCEDURE GetLogins( IN p_sid NVARCHAR(11)) 
COMMENT 'Логины для интерфейса' 
BEGIN 
    select login from Players natural join Users where id_session=p_sid order by id_player ; 
END



CREATE PROCEDURE ShowUsersTable( IN p_login NVARCHAR(11), IN p_sid NVARCHAR(11)) 
COMMENT 'Стол игрока по логину' 
BEGIN 
    select Cards.color as cardcolor, Cards.value as cardvalue, Players.color as color 
    from OnTable natural join Cards join Players on (OnTable.id_player=Players.id_player) natural join Users 
    where id_session=p_sid and Users.login=p_login ORDER BY cardcolor, cardvalue; 

    select 1 as sword from InHand natural join Players join Cards on Cards.id_card=InHand.id_card join Users on Players.id_user=Users.id_user where id_session=p_sid and Users.login=p_login and Cards.value="Меч" ; 
    select 1 as shield from InHand natural join Players join Cards on Cards.id_card=InHand.id_card join Users on Players.id_user=Users.id_user where id_session=p_sid and Users.login=p_login and Cards.value="Щит" ; 
END

CREATE PROCEDURE ShowTable( IN p_sid NVARCHAR(11)) 
SQL SECURITY INVOKER 
COMMENT 'INVOKER' 
BEGIN 
    select Users.login, Cards.color as cardcolor, Cards.value as cardvalue, Players.color as color 
    from OnTable natural join Cards join Players on (OnTable.id_player=Players.id_player) natural join Users 
    where id_session=p_sid ORDER BY Users.login, cardcolor, cardvalue; 
END




CREATE FUNCTION IsGameEnded(
    p_sid NVARCHAR(11)
) RETURNS BOOLEAN
COMMENT 'Проверка на конец игры (все щиты и мечи лежат на столе), параметры: id игровой сессии'
BEGIN
    DECLARE allCards INT DEFAULT 0;
    DECLARE mustbe INT DEFAULT 0;
    
    SELECT COUNT(*) INTO allCards 
    FROM OnTable 
    NATURAL JOIN Players 
    JOIN Cards ON (Cards.id_card = OnTable.id_card)
    WHERE id_session=p_sid AND (value='Меч' OR value='Щит');
    
    SELECT CAST(amount AS UNSIGNED)+3 INTO mustbe 
    FROM Sessions 
    WHERE id_session=p_sid;
    
    IF (allCards = mustbe*2) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END



CREATE PROCEDURE AddCardsToInHand( IN p_sid INT UNSIGNED) 
SQL SECURITY INVOKER 
COMMENT 'INVOKER' 
BEGIN 
    START TRANSACTION; 
    if (select COUNT(*) from InHand natural join Players where id_session = p_sid) > 0 then 
        select "Карты уже раздали" as error; 
    else 
        INSERT INTO InHand (id_player, id_card) 
        SELECT Players.id_player, Cards.id_card 
        FROM Players JOIN Cards ON Players.color = Cards.color 
        WHERE Players.id_session = p_sid and value <> Players.hero; 
    end if; 
    COMMIT; 
END


CREATE PROCEDURE StartTimer(IN p_sid INT) 
SQL SECURITY INVOKER 
COMMENT "INVOKER"
BEGIN 
    START TRANSACTION; 
        UPDATE Sessions SET startTime = CURTIME() WHERE id_session = p_sid; 
        call ClearMoves(p_sid);
    COMMIT; 
END;


CREATE PROCEDURE UpdateTimer(IN p_sid INT) 
COMMENT 'Взять значение таймера для обновления времени, параметры: id игровой сессии' 
SQL SECURITY DEFINER 
BEGIN 
    DECLARE seconds_diff INT; 
    SELECT turntime-TIMESTAMPDIFF(SECOND, startTime, CURTIME()) 
    INTO seconds_diff FROM Sessions WHERE id_session=p_sid; 
    
    select seconds_diff; 
    
    if (seconds_diff <= 0) then 
        select 1 as newTurn; 
        
        SET TRANSACTION ISOLATION LEVEL REPEATABLE READ; 
        START TRANSACTION; 
        UPDATE ChosenCards 
        SET id_card = (
            SELECT id_card FROM InHand natural join Cards join Players on InHand.id_player=Players.id_player 
            WHERE InHand.id_player = ChosenCards.id_player and Cards.value <> Players.hero 
            ORDER BY RAND() LIMIT 1) 
        where id_card =0 and id_player IN (select id_player from Players where id_session = p_sid); 
        
        UPDATE ChosenCards 
        SET id_enemy = (
            SELECT id_player FROM Players 
            WHERE Players.id_player <> ChosenCards.id_player and id_session=p_sid ORDER BY RAND() LIMIT 1)
        where id_enemy = 0 and id_player IN (select id_player from Players where id_session = p_sid); 
        
        UPDATE Players join ChosenCards on Players.id_player=ChosenCards.id_player 
        SET points = points + (SaveMove(Players.id_player, id_session, id_card, id_enemy)) 
        where id_session = p_sid; 
        
        
        
        call ClearMoves(p_sid); 
        
        UPDATE Sessions SET startTime = CURTIME() 
        WHERE id_session = p_sid; 
        
        
        
        if (IsGameEnded(p_sid) = TRUE) then 
            select 1 as gameEnded;
            Update Sessions 
            SET startTime=null where id_session=p_sid; 
            
            UPDATE Players natural join InHand join Cards on InHand.id_card=Cards.id_card 
            set points = points + 1 where id_session = p_sid and value="Холм"; 
            
             
        end if; 
        call ShowTable(p_sid); 
        COMMIT; 
    else 
        call ShowTable(p_sid); 
    end if; 
END


CREATE PROCEDURE ClearMoves(IN p_sid INT) 
COMMENT "INVOKER"
SQL SECURITY INVOKER 
BEGIN 

    START TRANSACTION; 
    UPDATE ChosenCards
    SET id_card = NULL, id_enemy=NULL
    WHERE id_player IN (
        SELECT id_player
        FROM Players
        WHERE id_session = p_sid
    );

    
    INSERT INTO ChosenCards (id_player, id_card)
    SELECT id_player, NULL
    FROM Players
    WHERE id_session = p_sid
    AND id_player NOT IN (
        SELECT id_player
        FROM ChosenCards
    );
        
    COMMIT; 
END;


CREATE PROCEDURE SaveCardForTurn(
    IN p_token INT UNSIGNED, 
    IN p_sid INT UNSIGNED, 
    IN p_cardname NVARCHAR(11), 
    IN p_enemy NVARCHAR(11)) 
COMMENT "Сохранить выбранную пользователем в ход карту, параметры: токен, id игровой сессии, название карты, логин игрока, на которого брошена карта"
SQL SECURITY DEFINER 
BEGIN 
    DECLARE idcard INT DEFAULT NULL; 
    DECLARE idplayer INT DEFAULT NULL; 
    DECLARE idp INT DEFAULT NULL; 
    DECLARE p_login NVARCHAR(11);
    SET p_login = GetLoginByToken(p_token);

    SELECT Players.id_player INTO idplayer
    FROM  Players
    NATURAL JOIN Users 
    WHERE login = p_login AND id_session = p_sid  for UPDATE;

    SELECT id_card INTO idcard  
    FROM InHand 
    NATURAL JOIN Cards 
    JOIN Players ON (Players.id_player = InHand.id_player) 
    NATURAL JOIN Users 
    WHERE login = p_login AND id_session = p_sid AND p_cardname = value for UPDATE;

    SELECT Players.id_player INTO idp
    from
    Players
    NATURAL JOIN Users 
    WHERE login = p_enemy AND id_session = p_sid;

    if (idp is null and (p_cardname='Меч' or p_cardname='Щит')) then 
        select 'Нет такого соперника' as error; 
    elseif (idplayer is null) then 
        select 'Неверный токен' as error; 
    elseif (idp = idplayer and (p_cardname='Меч' or p_cardname='Щит')) then
        select 'Нельзя кидать щит или меч на себя' as error; 
    elseif (idcard is null) then 
        select 'Такой карты нет в руке' as error; 
    else 
        UPDATE ChosenCards 
        SET id_card=idcard, id_enemy=idp 
        where id_player=idplayer; 
        select '1' as success; 
    end if; 
    
END


CREATE FUNCTION SaveMove(
    idplayer INT UNSIGNED, 
    p_sid INT, 
    idcard INT, 
    pid INT
) RETURNS INT
COMMENT 'INVOKER'
SQL SECURITY INVOKER 
BEGIN 
    DECLARE p_cardname NVARCHAR(11);
    DECLARE v_persX NVARCHAR(11);
    DECLARE v_pers NVARCHAR(11);
    DECLARE p_points INT DEFAULT 0;

    SELECT value INTO p_cardname FROM Cards WHERE id_card=idcard;

    IF (idcard IS NULL) OR (idplayer IS NULL) OR ((select COUNT(id_player) from Players ) = 0) OR ((select COUNT(id_card) from Cards ) = 0) OR ((select COUNT(id_card) from InHand where id_player=idplayer) = 0) THEN 
       
        RETURN 0;
    ELSE 
        IF (p_cardname = 'Меч') OR (p_cardname = 'Щит') THEN 
            
            IF (pid IS NULL) THEN 
                
                RETURN 0;
            ELSE 
                INSERT INTO OnTable (id_card, id_player) VALUES (idcard, pid); 
                DELETE FROM InHand WHERE id_card = idcard AND id_player = idplayer; 
                
                SET v_persX = (SELECT hero FROM Players WHERE id_player = pid);
                SET v_pers = (SELECT hero FROM Players WHERE id_player = idplayer);
                SET p_points = (
                CASE 
                    WHEN p_cardname = 'Меч' THEN
                        CASE 
                            WHEN (v_pers = 'Печенька' AND v_persX = 'Синий') THEN 3
                            WHEN (v_pers = 'Синий' AND v_persX = 'Стронций') THEN 3
                            WHEN (v_pers = 'Стронций' AND v_persX = '37') THEN 3
                            WHEN (v_pers = '37' AND v_persX = 'Персы') THEN 3
                            WHEN (v_pers = 'Персы' AND v_persX = 'Косинус') THEN 3
                            WHEN (v_pers = 'Косинус' AND v_persX = 'Печенька') THEN 3
                            ELSE 0
                        END
                    WHEN p_cardname = 'Щит' THEN
                        CASE 
                            WHEN (v_persX = 'Печенька' AND v_pers = 'Синий') THEN 2
                            WHEN (v_persX = 'Синий' AND v_pers = 'Стронций') THEN 2
                            WHEN (v_persX = 'Стронций' AND v_pers = '37') THEN 2
                            WHEN (v_persX = '37' AND v_pers = 'Персы') THEN 2
                            WHEN (v_persX = 'Персы' AND v_pers = 'Косинус') THEN 2
                            WHEN (v_persX = 'Косинус' AND v_pers = 'Печенька') THEN 2
                            ELSE 0
                        END
                    ELSE 0
                END;)
                RETURN p_points;
            END IF;
        ELSE 
            INSERT INTO OnTable (id_card, id_player) VALUES (idcard, idplayer); 
            DELETE FROM InHand WHERE id_card = idcard AND id_player = idplayer; 
        END IF; 
        RETURN 0;
    END IF; 
END




CREATE PROCEDURE ShowGames()
COMMENT "Показывает все игры, в которых участников недостаточно для начала"
SQL SECURITY DEFINER 
BEGIN
    SELECT s.id_session as games, 
    (SELECT COUNT(*) FROM Players WHERE id_session = s.id_session) as players, 
    amount as OutOf, 
    s.turnTime 
    FROM Sessions s 
    LEFT JOIN (SELECT id_session, COUNT(*) AS player_count FROM Players GROUP BY id_session) p ON s.id_session = p.id_session 
    WHERE (CAST(amount AS UNSIGNED) + 3 > player_count OR player_count IS NULL) 
        AND NOT EXISTS (SELECT 1 FROM SessionsLogs WHERE id_session = s.id_session);
END;


CREATE PROCEDURE SaveResult(IN p_token INT UNSIGNED, IN session_id INT)
COMMENT "Сохраняет результат игры для определенного игрока, параметры - токен, id сессии"

BEGIN
    DECLARE max_points INT;
    DECLARE user_id INT;
    DECLARE p_login NVARCHAR(11);
    SET p_login = GetLoginByToken(p_token);
    SET user_id = (select id_user from Users where login=p_login)
    start TRANSACTION;

    SELECT MAX(points) INTO max_points
    FROM Players
    WHERE id_session = session_id;
    
    select (Players.id_user IN (SELECT id_user FROM Players WHERE id_session = session_id AND points = max_points)) as wins, 
    points from Players join Users on Players.id_user=Users.id_user 
    where id_session=session_id and Users.login=p_login; 
    
    start TRANSACTION; 
    UPDATE Users 
    SET wins = wins + 1 
    WHERE id_user IN (SELECT id_user FROM Players WHERE id_session = session_id AND points = max_points); 
    
    UPDATE Users u natural JOIN Players p 
    SET u.game_points = u.game_points + p.points 
    WHERE p.id_session = session_id AND p.id_user = user_id; 
    
    
    
    COMMIT;
END;


CREATE PROCEDURE EndGame(session_id INT)
COMMENT "INVOKER"
SQL SECURITY INVOKER 
BEGIN
    DELETE FROM SessionsLogs
WHERE id_session = session_id;
    DELETE FROM Sessions
WHERE id_session = session_id;

END

