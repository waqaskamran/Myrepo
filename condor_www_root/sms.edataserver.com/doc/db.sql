-- ================================================================================
--   mysql SQL DDL Script File
-- ================================================================================


-- ===============================================================================
-- 
--   Generated by:      tedia2sql -- v1.2.12
--                      See http://tedia2sql.tigris.org/AUTHORS.html for tedia2sql author information
-- 
--   Target Database:   mysql
--   Generated at:      Wed Jan 11 14:47:15 2006
--   Input Files:       db.dia
-- 
-- ================================================================================



-- Generated SQL Constraints Drop statements
-- --------------------------------------------------------------------

drop index idx_comp_id_short on company;
drop index idx_modem_company_id on modem;
drop index idx_message_company_modem_ids on message;
drop index idx_status_message_id on message;
drop index idx_message_status_id on message_status_history;


-- Generated Permissions Drops
-- --------------------------------------------------------------------




-- Generated SQL View Drop Statements
-- --------------------------------------------------------------------



-- Generated SQL Schema Drop statements
-- --------------------------------------------------------------------

 drop table if exists company ;
 drop table if exists modem ;
 drop table if exists message ;
 drop table if exists message_status ;
 drop table if exists message_status_history ;
 drop table if exists campaign ;
 drop table if exists update_log ;


-- Generated SQL Schema
-- --------------------------------------------------------------------


-- company
create table company (
  date_modified             timstamp not null,
  date_created              timestamp not null,
  company_id                int unsigned auto_increment not null,
  company_name              varchar(255) not null,
  company_short             varchar(12) not null,
  email                     varchar(255) not null,
  constraint pk_Company primary key (company_id)
) ;

-- modem
create table modem (
  date_modified             timestamp not null,
  date_created              timestamp not null,
  modem_id                  int unsigned auto_increment not null,
  company_id                int unsigned not null,
  phone_number              varchar(14) not null,
  url                       varchar(255) not null,
  user_name                 varchar(255) not null,
  password                  varchar(255) not null,
  pin                       int unsigned not null,
  constraint pk_Modem primary key (modem_id)
) ;

-- message
create table message (
  date_modified             timestamp not null,
  date_created              timestamp not null,
  message_id                int unsigned auto_increment not null,
  company_id                int unsigned not null,
  modem_id                  int unsigned not null,
  campaign_id               int unsigned not null,
  message_status_id         int unsigned default no null,
  message                   varchar(180) null,
  attempts                  int(1) default not null default 0,
  type                      enum('incoming','outgoing') not null,
  priority                  int(2) default not null default '1',
  constraint pk_Message primary key (message_id)
) ;

-- message_status
create table message_status (
  date_created              timestamp not null,
  message_status_id         int unsigned auto_increment not null,
  status                    varchar(255) not null,
  constraint pk_Message_status primary key (message_status_id)
) ;

-- message_status_history
create table message_status_history (
  date_created              timestamp not null,
  message_status_history_id int unsigned auto_increment not null,
  message_id                int unsigned not null,
  message_status_id         int unsigned not null,
  constraint pk_Message_status_history primary key (message_status_history_id)
) ;

-- campaign
create table campaign (
  date_modified             timestamp not null,
  date_created              timestamp not null,
  campaign_id               int unsigned not null,
  company_id                int unsigned not null,
  campaign                  varchar(255) not null,
  default_message           varchar(180) null,
  constraint pk_Campaign primary key (campaign_id)
) ;

-- update_log
create table update_log (
  date_created              timestamp not null,
  update_log_id             int unsigned auto_increment not null,
  table_name                varchar(100) not null,
  column_id                 int unsigned not null,
  column_name               varchar(100) not null,
  value_before              mediumtext null,
  value_after               mediumtext null,
  constraint pk_Update_log primary key (update_log_id)
) ;









-- Generated SQL Views
-- --------------------------------------------------------------------




-- Generated Permissions
-- --------------------------------------------------------------------



-- Generated SQL Insert statements
-- --------------------------------------------------------------------



-- Generated SQL Constraints
-- --------------------------------------------------------------------

create index idx_comp_id_short on company  (company_id,company_short) ;
create index idx_modem_company_id on modem  (modem_id,company_id) ;
create index idx_message_company_modem_ids on message  (message_id,company_id,modem_id) ;
create index idx_status_message_id on message  (message_id,message_status_id) ;
create index idx_message_status_id on message_status_history  (message_id,message_status_id) ;

