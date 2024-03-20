
CREATE TABLE addresses (
    adrs_id      INTEGER NOT NULL,
    usr_id       INTEGER NOT NULL,
    adrs_line1   VARCHAR(255) NOT NULL,
    adrs_line2   VARCHAR(1023),
    adrs_zipcode VARCHAR(127) NOT NULL,
    adrs_city    VARCHAR(127) NOT NULL
);

CREATE UNIQUE INDEX addresses__idx ON
    addresses (
        usr_id
    ASC );

ALTER TABLE addresses ADD CONSTRAINT addresses_pk PRIMARY KEY ( adrs_id );

CREATE TABLE prices (
    price_id    INTEGER NOT NULL,
    price_price INTEGER NOT NULL,
    price_date  DATETIME NOT NULL,
    prod_id     INTEGER NOT NULL
);

CREATE UNIQUE INDEX prices__idx ON
    prices (
        prod_id
    ASC );

ALTER TABLE prices ADD CONSTRAINT prices_pk PRIMARY KEY ( price_id );

CREATE TABLE product_tags (
    prod_id INTEGER NOT NULL,
    tag_id  INTEGER NOT NULL
);

ALTER TABLE product_tags ADD CONSTRAINT product_tags_pk PRIMARY KEY ( prod_id,
                                                                      tag_id );

CREATE TABLE products (
    prod_id          INTEGER NOT NULL,
    prod_name_short  VARCHAR(255) NOT NULL,
    prod_name_long   VARCHAR(1023),
    prod_description VARCHAR(65535) NOT NULL,
    prod_image       VARCHAR(255) NOT NULL
);

ALTER TABLE products ADD CONSTRAINT products_pk PRIMARY KEY ( prod_id );

CREATE TABLE reviews (
    rev_id    INTEGER NOT NULL,
    rev_score INTEGER NOT NULL,
    rev_text  VARCHAR(65535),
    rev_date  DATETIME NOT NULL,
    prod_id   INTEGER NOT NULL
);

ALTER TABLE reviews ADD CONSTRAINT reviews_pk PRIMARY KEY ( rev_id );

CREATE TABLE roles (
    role_id   INTEGER NOT NULL,
    role_name VARCHAR(32) NOT NULL
);

ALTER TABLE roles ADD CONSTRAINT roles_pk PRIMARY KEY ( role_id );

CREATE TABLE tags (
    tag_id    INTEGER NOT NULL,
    tag_descr VARCHAR(32)
);

ALTER TABLE tags ADD CONSTRAINT tags_pk PRIMARY KEY ( tag_id );

CREATE TABLE transaction_products (
    prod_id           INTEGER NOT NULL,
    trans_id          INTEGER NOT NULL,
    trans_prod_amount INTEGER NOT NULL
);

ALTER TABLE transaction_products ADD CONSTRAINT transaction_products_pk PRIMARY KEY ( prod_id,
                                                                                      trans_id );

CREATE TABLE transactions (
    trans_id        INTEGER NOT NULL,
    usr_id          INTEGER NOT NULL,
    adrs_id         INTEGER NOT NULL,
    trans_buy_date  DATETIME NOT NULL,
    trans_sent_date DATETIME,
    trans_recieved  DOUBLE NOT NULL
);

CREATE UNIQUE INDEX transactions__idx ON
    transactions (
        adrs_id
    ASC );

ALTER TABLE transactions ADD CONSTRAINT transactions_pk PRIMARY KEY ( trans_id );

CREATE TABLE users (
    usr_id       INTEGER NOT NULL,
    usr_name     VARCHAR(64) NOT NULL,
    usr_password VARCHAR(64) NOT NULL,
    usr_date     DATETIME NOT NULL,
    usr_email    VARCHAR(255) NOT NULL,
    role_id      INTEGER NOT NULL
);

ALTER TABLE users ADD CONSTRAINT users_pk PRIMARY KEY ( usr_id );

ALTER TABLE addresses
    ADD CONSTRAINT addresses_users_fk FOREIGN KEY ( usr_id )
        REFERENCES users ( usr_id );

ALTER TABLE prices
    ADD CONSTRAINT prices_products_fk FOREIGN KEY ( prod_id )
        REFERENCES products ( prod_id );

ALTER TABLE product_tags
    ADD CONSTRAINT product_tags_products_fk FOREIGN KEY ( prod_id )
        REFERENCES products ( prod_id );

ALTER TABLE product_tags
    ADD CONSTRAINT product_tags_tags_fk FOREIGN KEY ( tag_id )
        REFERENCES tags ( tag_id );

ALTER TABLE reviews
    ADD CONSTRAINT reviews_products_fk FOREIGN KEY ( prod_id )
        REFERENCES products ( prod_id );

ALTER TABLE transaction_products
    ADD CONSTRAINT trans_prod_products_fk FOREIGN KEY ( prod_id )
        REFERENCES products ( prod_id );

ALTER TABLE transaction_products
    ADD CONSTRAINT trans_prod_transactions_fk FOREIGN KEY ( trans_id )
        REFERENCES transactions ( trans_id );

ALTER TABLE transactions
    ADD CONSTRAINT transactions_addresses_fk FOREIGN KEY ( adrs_id )
        REFERENCES addresses ( adrs_id );

ALTER TABLE transactions
    ADD CONSTRAINT transactions_users_fk FOREIGN KEY ( usr_id )
        REFERENCES users ( usr_id );

ALTER TABLE users
    ADD CONSTRAINT users_roles_fk FOREIGN KEY ( role_id )
        REFERENCES roles ( role_id );
		