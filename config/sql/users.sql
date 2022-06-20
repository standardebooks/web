create user 'alex'@'localhost' identified by unix_socket;
create user 'se'@'localhost' identified by unix_socket;
create user 'www-data'@'localhost' identified by unix_socket;

grant * on * to alex@localhost identified via unix_socket;
grant select, insert, update, delete, execute on se.* to se@localhost identified via unix_socket;
grant select, insert, update, delete, execute on se.* to www-data@localhost identified via unix_socket;

flush privileges;
