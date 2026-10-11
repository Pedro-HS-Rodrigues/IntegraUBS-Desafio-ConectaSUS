CREATE DATABASE IF NOT EXISTS integraubs
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE integraubs;

CREATE TABLE health_units (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE patients (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    cpf CHAR(11) NOT NULL,
    birth_date DATE NOT NULL,
    phone VARCHAR(20) NULL DEFAULT NULL,
    health_unit_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY patients_cpf_unique (cpf),
    KEY patients_name_index (name),
    CONSTRAINT patients_health_unit_id_foreign
        FOREIGN KEY (health_unit_id)
        REFERENCES health_units (id)
        ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
