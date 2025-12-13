-- PostgreSQL Compatible SQL Dump
-- Original file generated from MySQL/MariaDB (phpMyAdmin 5.2.1)

-- 1. Remove MySQL SET commands (SQL_MODE, time_zone, CHARACTER_SET, etc.)
-- These are not recognized by PostgreSQL and cause errors.
START TRANSACTION;

-- --------------------------------------------------------

--
-- Table structure for table "cache"
--

CREATE TABLE "cache" (
    -- Changed backticks to double quotes.
    "key" character varying(255) NOT NULL, -- Changed varchar to character varying
    "value" text NOT NULL, -- Changed mediumtext to text
    "expiration" integer NOT NULL -- Changed int(11) to integer
);

-- --------------------------------------------------------

--
-- Table structure for table "cache_locks"
--

CREATE TABLE "cache_locks" (
    -- Changed backticks to double quotes.
    "key" character varying(255) NOT NULL,
    "owner" character varying(255) NOT NULL,
    "expiration" integer NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "failed_jobs"
--

CREATE TABLE "failed_jobs" (
    -- Changed bigint(20) UNSIGNED NOT NULL to BIGSERIAL PRIMARY KEY for auto-increment
    "id" BIGSERIAL PRIMARY KEY,
    "uuid" character varying(255) NOT NULL,
    "connection" text NOT NULL,
    "queue" text NOT NULL,
    "payload" text NOT NULL, -- Changed longtext to text
    "exception" text NOT NULL, -- Changed longtext to text
    -- Removed DEFAULT current_timestamp() from NOT NULL timestamp to prevent syntax error on creation
    "failed_at" timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------

--
-- Table structure for table "jobs"
--

CREATE TABLE "jobs" (
    -- Changed bigint(20) UNSIGNED NOT NULL to BIGSERIAL PRIMARY KEY
    "id" BIGSERIAL PRIMARY KEY,
    "queue" character varying(255) NOT NULL,
    "payload" text NOT NULL, -- Changed longtext to text
    "attempts" smallint NOT NULL, -- Changed tinyint(3) UNSIGNED to smallint
    "reserved_at" integer DEFAULT NULL, -- Changed int(10) UNSIGNED to integer
    "available_at" integer NOT NULL, -- Changed int(10) UNSIGNED to integer
    "created_at" integer NOT NULL -- Changed int(10) UNSIGNED to integer
);

-- --------------------------------------------------------

--
-- Table structure for table "job_batches"
--

CREATE TABLE "job_batches" (
    -- Changed backticks to double quotes.
    "id" character varying(255) NOT NULL PRIMARY KEY,
    "name" character varying(255) NOT NULL,
    "total_jobs" integer NOT NULL,
    "pending_jobs" integer NOT NULL,
    "failed_jobs" integer NOT NULL,
    "failed_job_ids" text NOT NULL, -- Changed longtext to text
    "options" text DEFAULT NULL, -- Changed mediumtext to text
    "cancelled_at" integer DEFAULT NULL,
    "created_at" integer NOT NULL,
    "finished_at" integer DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "migrations"
--

CREATE TABLE "migrations" (
    -- Changed int(10) UNSIGNED NOT NULL to SERIAL PRIMARY KEY
    "id" SERIAL PRIMARY KEY,
    "migration" character varying(255) NOT NULL,
    "batch" integer NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "orders"
--

-- PostgreSQL does not support MySQL's ENUM type directly.
-- It is converted here to a check constraint on a character varying field.
CREATE TABLE "orders" (
    -- Changed bigint(20) UNSIGNED NOT NULL to BIGSERIAL PRIMARY KEY
    "id" BIGSERIAL PRIMARY KEY,
    "order_number" character varying(255) NOT NULL,
    -- Removed UNSIGNED from bigint(20)
    "user_id" bigint DEFAULT NULL,
    "customer_name" character varying(255) NOT NULL,
    "contact_number" character varying(255) NOT NULL,
    "total_amount" numeric(10,2) NOT NULL, -- Changed decimal to numeric
    -- Converted MySQL ENUM to character varying with CHECK constraint
    "status" character varying(255) NOT NULL DEFAULT 'pending'
        CHECK ("status" IN ('pending', 'processing', 'completed', 'cancelled')),
    "notes" text DEFAULT NULL,
    "created_at" timestamp without time zone DEFAULT NULL,
    "updated_at" timestamp without time zone DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "order_items"
--

CREATE TABLE "order_items" (
    "id" BIGSERIAL PRIMARY KEY,
    -- Removed UNSIGNED from bigint(20)
    "order_id" bigint NOT NULL,
    -- Removed UNSIGNED from bigint(20)
    "product_id" bigint NOT NULL,
    "product_name" character varying(255) NOT NULL,
    "price" numeric(10,2) NOT NULL, -- Changed decimal to numeric
    "quantity" integer NOT NULL,
    "subtotal" numeric(10,2) NOT NULL, -- Changed decimal to numeric
    "created_at" timestamp without time zone DEFAULT NULL,
    "updated_at" timestamp without time zone DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "password_reset_tokens"
--

CREATE TABLE "password_reset_tokens" (
    "email" character varying(255) NOT NULL PRIMARY KEY, -- Added PRIMARY KEY here as it's the key in MySQL
    "token" character varying(255) NOT NULL,
    "created_at" timestamp without time zone DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "products"
--

CREATE TABLE "products" (
    "id" BIGSERIAL PRIMARY KEY,
    "name" character varying(255) NOT NULL,
    "description" text NOT NULL,
    "price" numeric(10,2) NOT NULL, -- Changed decimal to numeric
    "image" character varying(255) DEFAULT NULL,
    "stock" integer NOT NULL DEFAULT 0,
    "created_at" timestamp without time zone DEFAULT NULL,
    "updated_at" timestamp without time zone DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "sessions"
--

CREATE TABLE "sessions" (
    "id" character varying(255) NOT NULL PRIMARY KEY,
    -- Removed UNSIGNED from bigint(20)
    "user_id" bigint DEFAULT NULL,
    "ip_address" character varying(45) DEFAULT NULL,
    "user_agent" text DEFAULT NULL,
    "payload" text NOT NULL, -- Changed longtext to text
    "last_activity" integer NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table "users"
--

CREATE TABLE "users" (
    "id" BIGSERIAL PRIMARY KEY,
    "name" character varying(255) NOT NULL,
    "email" character varying(255) NOT NULL,
    "email_verified_at" timestamp without time zone DEFAULT NULL,
    "password" character varying(255) NOT NULL,
    "remember_token" character varying(100) DEFAULT NULL,
    "created_at" timestamp without time zone DEFAULT NULL,
    "updated_at" timestamp without time zone DEFAULT NULL
);

-- Now that tables are created, you would typically add primary keys, indexes, and foreign keys here.
-- Assuming they were defined outside the CREATE TABLE block in the original script or you will define them later.
-- For Laravel-style schemas, PRIMARY KEY is often defined inline.

-- Add necessary unique constraints for the Laravel tables:
ALTER TABLE "cache" ADD PRIMARY KEY ("key");
ALTER TABLE "cache_locks" ADD PRIMARY KEY ("key");
ALTER TABLE "failed_jobs" ADD UNIQUE ("uuid");
ALTER TABLE "users" ADD UNIQUE ("email");
ALTER TABLE "sessions" ADD UNIQUE ("id");

-- Add foreign keys (assuming they were missing in the original dump and are necessary):
-- Example:
-- ALTER TABLE "order_items" ADD CONSTRAINT "fk_order_items_order_id" FOREIGN KEY ("order_id") REFERENCES "orders"("id") ON DELETE CASCADE;
-- ALTER TABLE "order_items" ADD CONSTRAINT "fk_order_items_product_id" FOREIGN KEY ("product_id") REFERENCES "products"("id") ON DELETE RESTRICT;

COMMIT;

-- IMPORTANT: You will still need to manually copy and paste the INSERT statements for data, 
-- and ensure that the values match the new PostgreSQL data types (e.g., date formats).