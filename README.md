# poosd-lamp-stack-g19
COP 4331 - POOSD Lamp Stack Mini Project Repository - Group 19

Hi hello

Setup with MySQL database:
```sql
create database ContactManager;
use ContactManager;
-- Create perms for the backend user
create user 'lamp_G19'@'%' identified by 'WeLoveCOP4331';
grant all privileges on ContactManager.* to 'lamp_G19'@'%';
create table Users(
    id int auto_increment primary key,
    firstname varchar(255) not null,
    lastname varchar(255),
    email varchar(255) unique,
    created_at timestamp default current_timestamp,
    password varchar(255)
) ENGINE = InnoDB;
create table Contacts(
    id int auto_increment primary key,
    parent_id int not null,
    firstname varchar(255) not null,
    lastname varchar(255),
    email varchar(255),
    phone varchar(100) not null,
    company varchar(255),
    created_at timestamp default current_timestamp,
    foreign key (parent_id) references Users(id)
    on delete cascade
) ENGINE = InnoDB;
```

Example values:
```sql
-- firstname, lastname, email, password
insert into Users(firstname, lastname, email, password) values('Ozair', 'Ahmed', 'oz@email.com', 'Password123');
-- ParentID (required, and must be linked to an existing user), firstname, lastname, email, phone, company
insert into Contacts(parent_id, firstname, lastname, email, phone, company) values(1, 'John', 'Aedo', 'john@aedo.com', '123-456-7890', 'University of Central Florida');
```