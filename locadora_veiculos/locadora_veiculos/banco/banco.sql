create table estado_veiculo
(
    id   serial
        constraint estado_veiculo_pkey
            primary key,
    nome varchar(20) not null
);

alter table estado_veiculo
    owner to postgres;

create table estado_reserva
(
    id   serial
        constraint estado_reserva_pkey
            primary key,
    nome varchar(20) not null
);

alter table estado_reserva
    owner to postgres;

create table veiculo
(
    id                serial
        constraint veiculo_pkey
            primary key,
    marca             varchar(30) not null,
    model             varchar(30) not null,
    placa             varchar(8)  not null
        constraint veiculo_placa_key
            unique,
    ano               varchar(4)  not null,
    id_estado_veiculo integer     not null
        constraint veiculo_id_estado_veiculo_fkey
            references estado_veiculo,
    combustivel       varchar(20) not null
);

alter table veiculo
    owner to postgres;

create table reserva
(
    id                serial
        constraint reserva_pkey
            primary key,
    data_inicio       timestamp not null,
    data_fim          timestamp not null,
    id_veiculo        integer
        constraint id_veiculo
            references veiculo,
    id_estado_reserva integer   not null
        constraint reserva_id_estado_reserva_fkey
            references estado_reserva
);

alter table reserva
    owner to postgres;

create table hora
(
    id   serial
        constraint hora_pkey
            primary key,
    hora varchar(5) not null
);

alter table hora
    owner to postgres;


