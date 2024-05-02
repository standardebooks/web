create user 'alex'@'localhost' identified via unix_socket;
create user 'se'@'localhost' identified via unix_socket;
create user 'www-data'@'localhost' identified via unix_socket;
create user 'se-vcs-bot'@'localhost' identified via unix_socket;

grant * on * to alex@localhost identified via unix_socket;
grant select, insert, update, delete, execute, lock tables on se.* to 'se'@'localhost' identified via unix_socket;
grant select, insert, update, delete, execute on se.* to 'www-data'@'localhost' identified via unix_socket;
grant select, insert, update, delete, execute on se.* to 'se-vcs-bot'@'localhost' identified via unix_socket;

flush privileges;
