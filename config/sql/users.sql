create user if not exists 'se'@'localhost' identified via unix_socket;
create user if not exists 'www-data'@'localhost' identified via unix_socket;
create user if not exists 'se-vcs-bot'@'localhost' identified via unix_socket;

grant select, insert, update, delete, execute, lock tables on se.* to 'se'@'localhost' identified via unix_socket;
grant select, insert, update, delete, execute on se.* to 'www-data'@'localhost' identified via unix_socket;
grant select, insert, update, delete, execute on se.* to 'se-vcs-bot'@'localhost' identified via unix_socket;

flush privileges;
