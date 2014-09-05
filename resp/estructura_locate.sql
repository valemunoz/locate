CREATE TABLE mx_cliente
(
  id_cliente serial NOT NULL,
  nombre character varying,
  estado numeric, -- 0=activo...
  logo character varying(500)
)WITH (
  OIDS=FALSE
);

CREATE TABLE mx_empresa
(
  id_empresa serial NOT NULL,
  nombre character varying(300),
  calle character varying(300),
  numero_municipal character varying(20),
  comuna character varying(300),
  region character varying(100),
  latitud numeric,
  longitud numeric,
  geom geometry,
  fecha_registro timestamp without time zone,
  otro character varying(500),
  id_cliente numeric,
  CONSTRAINT mx_empresa_pkey PRIMARY KEY (id_empresa)
)
WITH (
  OIDS=FALSE
);
CREATE TABLE mx_registro_obvii
(
  id_registro serial NOT NULL,
  id_usuario character varying(50),
  fecha_hora timestamp without time zone,
  latitud numeric,
  longitud numeric,
  id_empresa numeric,
  distancia double precision,
  "precision" double precision,
  id_cliente numeric,
  estado numeric DEFAULT 0,
  CONSTRAINT mx_registro_obvii_pkey PRIMARY KEY (id_registro)
)
WITH (
  OIDS=FALSE
);
CREATE TABLE mx_registro_usuario_locate
(
  id_reg_usuario serial NOT NULL,
  id_usuario numeric,
  fecha timestamp without time zone,
  estado numeric, -- 0=exitoso...
  usuario character varying(300),
  clave character varying(300),
  id_cliente numeric,
  CONSTRAINT mx_registro_usuario_locate_pkey PRIMARY KEY (id_reg_usuario)
)
WITH (
  OIDS=FALSE
);
CREATE TABLE mx_usuarios_obvii
(
  id_usuario serial NOT NULL,
  nombre character varying(400),
  id_cliente numeric,
  apellido character varying(400),
  fecha_registro time without time zone,
  mail character varying(400),
  clave character varying(10),
  estado numeric, -- 0=activo...
  tipo_usuario numeric DEFAULT 0,
  CONSTRAINT mx_usuarios_obvii_pkey PRIMARY KEY (id_usuario)
)
WITH (
  OIDS=FALSE
);
