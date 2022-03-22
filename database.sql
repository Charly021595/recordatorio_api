CREATE DATABASE IF NOT EXISTS recordatorio_api_laravel;
USE recordatorio_api_laravel;

CREATE TABLE usuarios(
    id int(255) auto_increment not null,
    role varchar(20),
    name varchar(255),
    surname varchar(255),
    password varchar(255),
    created_at timestamp,
    updated_at timestamp,
    remember_token varchar(255),
    CONSTRAINT pk_usuarios PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE autos(
    id int(255) auto_increment not null,
    usuario_id int(255) not null,
    titulo varchar(255),
    descripcion text,
    precio varchar(30),
    status varchar(30),
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    CONSTRAINT pk_autos PRIMARY KEY(id),
    CONSTRAINT pk_autos_usuarios FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
)ENGINE=InnoDb;