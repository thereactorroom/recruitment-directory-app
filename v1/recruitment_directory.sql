

create database if not exists recruitment_directory_uat;
use recruitment_directory_uat;

-- this identifiers the community
create table unique_key (
    id int not null auto_increment,
    community_id int not null default 0,
    content_id int not null default 0,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id)
) ENGINE=InnoDB CHARACTER SET utf8;

-- identifies the user of the content
create table user_key (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    user_id int not null default 0, -- fusion user_id
    mobile varchar(16) not null default '',
    country_code varchar(8) not null default '',
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table module_config (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    app_title varchar(256) not null default '',
    base44_specials_url varchar(256) not null default '',
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table proposition (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    title varchar(128) not null default '',
    proposition text null,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table sort_index (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    listings varchar(128) not null default '1',
    referrals varchar(128) not null default '-1',
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table listings (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    user_key_id int not null default 0,
    sort_index int not null default 0,

    name varchar(255) not null default '',
    description text null,
    country_code varchar(8) not null default '',
    contact_number varchar(16) not null default '',
    office_number varchar(16) not null default '',
    whatsapp varchar(16) not null default '',
    email varchar(256) not null default '',
    dob varchar(64) not null default '',
    gender varchar(16) not null default '',
    location varchar(512) not null default '',
    cv_path varchar(1024) not null default '',
    logo varchar(256) not null default '',
    google_url varchar(1024) not null default '',
    website_url varchar(1024) not null default '',
    facebook_url varchar(1024) not null default '',
    x_url varchar(1024) not null default '',
    instagram_url varchar(1024) not null default '',

    vat_number varchar(128) not null default '',
    registration_number varchar(128) not null default '',

    comments int not null default 0,
    stars_avg int not null default 0,
    referral tinyint(1) not null default 0,
    status varchar(128) not null default 'draft', -- Draft, Waiting Approval, Active, Rejected, 
    downgraded tinyint(1) not null default 0,
    date_downgraded datetime null,

    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict,
    foreign key(user_key_id) references user_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table listing_statuses (
    id int not null auto_increment,
    listing_id int not null default 0,
    status varchar(64) not null default '',
    comment varchar(1024) not null default '',
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id)
) ENGINE=InnoDB CHARACTER SET utf8;

create table sections (
    id int not null auto_increment,
    listing_id int not null default 0,
    details tinyint(1) not null default 0,
    payment tinyint(1) not null default 0,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id)
) ENGINE=InnoDB CHARACTER SET utf8;

create table comments (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    user_key_id int not null default 0,
    listing_id int not null default 0,
    comment varchar(1024) not null default '',
    stars int not null default 0,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict,
    foreign key(user_key_id) references user_key(id) on update cascade on delete restrict,
    foreign key(listing_id) references listings(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table favorites (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    user_key_id int not null default 0,
    listing_id int not null default 0,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict,
    foreign key(user_key_id) references user_key(id) on update cascade on delete restrict,
    foreign key(listing_id) references listings(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table referrals (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    user_key_id int not null default 0, -- referee
    listing_id int not null default 0,
    last_called varchar(64) not null default '',
    closed tinyint(1) not null default 0,
    closed_by int not null default 0, -- user_id
    closed_date varchar(64) not null default '',
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict,
    foreign key(user_key_id) references user_key(id) on update cascade on delete restrict,
    foreign key(listing_id) references listings(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table call_log (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    user_key_id int not null default 0,
    referral_id int not null default 0,
    type varchar(64) not null default 'referral', -- referral, business
    type_id int not null default 0,
    comment text null,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict,
    foreign key(user_key_id) references user_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table blacklisted (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    user_key_id int not null default 0,
    member_id int not null default 0,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict,
    foreign key(user_key_id) references user_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table subscriptions (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    listing_id int not null default 0,
    first_payment decimal not null default 0.0,
    recurring_payment decimal not null default 0.0,
    billing_cycle varchar(128) not null default 'Monthly',
    payment_method varchar(128) not null default 'EFT',
    last_billing datetime not null default current_timestamp,
    next_billing datetime not null default current_timestamp,
    status varchar(64) not null default 'pending', -- Not Paid, Paid, Downgraded
    paid tinyint(1) not null default 0,
    payment_date datetime null,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table payfast_response_log (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    subscription_id int not null default 0,
    status varchar(16) not null default '',
    payload json null,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id)
) ENGINE=InnoDB CHARACTER SET utf8;

create table payment_logs (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    listing_id int not null default 0,
    subscription_id int not null default 0,
    date_paid varchar(64) not null default '',
    amount decimal not null default 0.0,
    method varchar(128) not null default 'PayFast', -- Voucher
    comment varchar(128) not null default '', -- Voucher
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id)
) ENGINE=InnoDB CHARACTER SET utf8;

create table vouchers (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    code varchar(64) not null default '',
    capacity integer not null default 1,
    used integer not null default 0,
    available integer not null default 1,
    period_type varchar(64) not null default '', -- 12 months
    period_length int not null default 1,
    description varchar(512) not null default '',
    start_date varchar(64) not null default '',
    end_date varchar(64) not null default '',
    is_active tinyint(1) not null default 1,
    closed_date varchar(64) not null default '', -- update also with cron, and when used up, or expired
    closed_by varchar(64) not null default '',
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

create table voucher_usage (
    id int not null auto_increment,
    unique_key_id int not null default 0,
    voucher_id int not null default 0,
    listing_id int not null default 0,
    added datetime default current_timestamp,
    updated datetime on update current_timestamp,
    deleted varchar(64) not null default '',
    deleted_by varchar(64) not null default '',
    primary key(id),
    foreign key(unique_key_id) references unique_key(id) on update cascade on delete restrict,
    foreign key(listing_id) references listings(id) on update cascade on delete restrict
) ENGINE=InnoDB CHARACTER SET utf8;

