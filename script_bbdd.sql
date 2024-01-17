CREATE TABLE tipo_monitor (
                              id SERIAL PRIMARY KEY,
                              descripcion VARCHAR(255)
);

CREATE TABLE turno (
                       id SERIAL PRIMARY KEY,
                       descripcion VARCHAR(255)
);

CREATE TABLE monitor (
                         id SERIAL PRIMARY KEY,
                         nombre VARCHAR(255),
                         apellidos VARCHAR(255),
                         dni VARCHAR(9),
                         fecha_nacimiento DATE,
                         id_turno INT NOT NULL,
                         CONSTRAINT fk_turno FOREIGN KEY (id_turno) REFERENCES turno (id)
);

CREATE TABLE monitor_tipo (
                              id_monitor INT NOT NULL,
                              id_tipo INT NOT NULL,
                              PRIMARY KEY (id_monitor, id_tipo),
                              CONSTRAINT fk_monitor FOREIGN KEY (id_monitor) REFERENCES monitor (id) ON DELETE CASCADE,
                              CONSTRAINT fk_tipo FOREIGN KEY (id_tipo) REFERENCES tipo_monitor (id) ON DELETE CASCADE
);

CREATE TABLE cliente (
                         id SERIAL PRIMARY KEY,
                         nombre VARCHAR(255),
                         apellidos VARCHAR(255),
                         dni VARCHAR(9),
                         fecha_nacimiento DATE
);


CREATE TABLE clase (
                       id SERIAL PRIMARY KEY,
                       nombre VARCHAR(255),
                       fecha TIMESTAMP(6),
                       duracion INT,
                       aforo_max INT,
                       id_monitor INT NOT NULL,
                       CONSTRAINT fk_clase_monitor FOREIGN KEY (id_monitor) REFERENCES monitor (id)
);

CREATE TABLE cliente_clase (
                               id_cliente INT NOT NULL,
                               id_clase INT NOT NULL,
                               PRIMARY KEY (id_cliente, id_clase),
                               CONSTRAINT fk_cliente_cc FOREIGN KEY (id_cliente) REFERENCES cliente (id),
                               CONSTRAINT fk_clase_cc FOREIGN KEY (id_clase) REFERENCES clase (id)
);


CREATE TABLE tipo_abono (
                            id SERIAL PRIMARY KEY,
                            descripcion VARCHAR(150),
                            precio float
);

CREATE TABLE abono (
                       id SERIAL PRIMARY KEY,
                       codigo VARCHAR(150),
                       fecha_caducidad TIMESTAMP(6),
                       id_tipo_abono INT,
                       id_cliente INT NOT NULL,
                       CONSTRAINT fk_abono_cliente FOREIGN KEY (id_cliente) REFERENCES cliente (id),
                       CONSTRAINT fk_abono_tipo_abono FOREIGN KEY (id_tipo_abono) REFERENCES tipo_abono (id)
);