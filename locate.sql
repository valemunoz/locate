--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.4
-- Dumped by pg_dump version 9.2.4
-- Started on 2014-09-08 15:24:06

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 250 (class 1259 OID 101321)
-- Name: mx_agenda_obvii; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_agenda_obvii (
    id_agenda integer NOT NULL,
    id_usuario numeric,
    fecha_agenda date,
    id_empresa numeric,
    id_categoria_usuario numeric,
    estado numeric,
    descripcion character varying(500),
    id_cliente numeric,
    fecha_registro timestamp without time zone,
    hora time without time zone
);


ALTER TABLE public.mx_agenda_obvii OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 101319)
-- Name: mx_agenda_obvii_id_agenda_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_agenda_obvii_id_agenda_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_agenda_obvii_id_agenda_seq OWNER TO postgres;

--
-- TOC entry 3347 (class 0 OID 0)
-- Dependencies: 249
-- Name: mx_agenda_obvii_id_agenda_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_agenda_obvii_id_agenda_seq OWNED BY mx_agenda_obvii.id_agenda;


--
-- TOC entry 218 (class 1259 OID 84495)
-- Name: mx_archivos_obvii; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_archivos_obvii (
    id_archivo integer NOT NULL,
    id_registro numeric,
    fecha_subida timestamp without time zone,
    nombre character varying(300),
    path character varying(500),
    detalle character varying(500)
);


ALTER TABLE public.mx_archivos_obvii OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 84493)
-- Name: mx_archivos_obvii_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_archivos_obvii_id_archivo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_archivos_obvii_id_archivo_seq OWNER TO postgres;

--
-- TOC entry 3348 (class 0 OID 0)
-- Dependencies: 217
-- Name: mx_archivos_obvii_id_archivo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_archivos_obvii_id_archivo_seq OWNED BY mx_archivos_obvii.id_archivo;


--
-- TOC entry 252 (class 1259 OID 101330)
-- Name: mx_categoria_usuario_obvii; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_categoria_usuario_obvii (
    id_categoria_usuario integer NOT NULL,
    nombre character varying(300),
    estado numeric,
    id_cliente numeric
);


ALTER TABLE public.mx_categoria_usuario_obvii OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 101328)
-- Name: mx_categoria_usuario_obvii_id_categoria_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_categoria_usuario_obvii_id_categoria_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_categoria_usuario_obvii_id_categoria_usuario_seq OWNER TO postgres;

--
-- TOC entry 3349 (class 0 OID 0)
-- Dependencies: 251
-- Name: mx_categoria_usuario_obvii_id_categoria_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_categoria_usuario_obvii_id_categoria_usuario_seq OWNED BY mx_categoria_usuario_obvii.id_categoria_usuario;


--
-- TOC entry 256 (class 1259 OID 117974)
-- Name: mx_producto_detalle_obvii; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_producto_detalle_obvii (
    id_det_prod integer NOT NULL,
    id_producto numeric,
    id_usuario numeric,
    id_cliente numeric,
    inicio numeric,
    fin numeric,
    venta numeric,
    estado numeric,
    comentario character varying(500),
    id_registro numeric,
    fecha_registro timestamp without time zone,
    valor numeric,
    cantidad numeric
);


ALTER TABLE public.mx_producto_detalle_obvii OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 117972)
-- Name: mx_check_producto_detalle_id_det_prod_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_check_producto_detalle_id_det_prod_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_check_producto_detalle_id_det_prod_seq OWNER TO postgres;

--
-- TOC entry 3350 (class 0 OID 0)
-- Dependencies: 255
-- Name: mx_check_producto_detalle_id_det_prod_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_check_producto_detalle_id_det_prod_seq OWNED BY mx_producto_detalle_obvii.id_det_prod;


--
-- TOC entry 254 (class 1259 OID 117965)
-- Name: mx_producto_obvii; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_producto_obvii (
    id_producto integer NOT NULL,
    id_cliente numeric,
    nombre character varying(300),
    fecha_registro timestamp without time zone,
    estado numeric,
    imagen character varying(600),
    path_img character varying(600),
    valor numeric
);


ALTER TABLE public.mx_producto_obvii OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 117963)
-- Name: mx_check_producto_id_producto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_check_producto_id_producto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_check_producto_id_producto_seq OWNER TO postgres;

--
-- TOC entry 3351 (class 0 OID 0)
-- Dependencies: 253
-- Name: mx_check_producto_id_producto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_check_producto_id_producto_seq OWNED BY mx_producto_obvii.id_producto;


--
-- TOC entry 202 (class 1259 OID 51540)
-- Name: mx_cliente; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_cliente (
    id_cliente integer NOT NULL,
    nombre character varying,
    estado numeric,
    logo character varying(500),
    app numeric,
    fecha_inicio date,
    fecha_termino date,
    max_lugares numeric,
    max_usuarios numeric,
    fecha_registro timestamp without time zone,
    pais character varying(200),
    mail_pedido character varying(300)
);


ALTER TABLE public.mx_cliente OWNER TO postgres;

--
-- TOC entry 3352 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN mx_cliente.estado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN mx_cliente.estado IS '0=activo
1=inactivo';


--
-- TOC entry 3353 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN mx_cliente.app; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN mx_cliente.app IS 'solo para locate';


--
-- TOC entry 201 (class 1259 OID 51538)
-- Name: mx_cliente_id_cliente_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_cliente_id_cliente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_cliente_id_cliente_seq OWNER TO postgres;

--
-- TOC entry 3354 (class 0 OID 0)
-- Dependencies: 201
-- Name: mx_cliente_id_cliente_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_cliente_id_cliente_seq OWNED BY mx_cliente.id_cliente;


--
-- TOC entry 204 (class 1259 OID 51570)
-- Name: mx_empresa; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_empresa (
    id_empresa integer NOT NULL,
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
    estado numeric DEFAULT 0,
    anexo character varying(200),
    pais character varying(200)
);


ALTER TABLE public.mx_empresa OWNER TO postgres;

--
-- TOC entry 3355 (class 0 OID 0)
-- Dependencies: 204
-- Name: TABLE mx_empresa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE mx_empresa IS 'tabla empresa para app obvi';


--
-- TOC entry 203 (class 1259 OID 51568)
-- Name: mx_empresa_id_empresa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_empresa_id_empresa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_empresa_id_empresa_seq OWNER TO postgres;

--
-- TOC entry 3356 (class 0 OID 0)
-- Dependencies: 203
-- Name: mx_empresa_id_empresa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_empresa_id_empresa_seq OWNED BY mx_empresa.id_empresa;


--
-- TOC entry 206 (class 1259 OID 68052)
-- Name: mx_registro_obvii; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_registro_obvii (
    id_registro integer NOT NULL,
    id_usuario character varying(50),
    fecha_hora timestamp without time zone,
    latitud numeric,
    longitud numeric,
    id_empresa numeric,
    distancia double precision,
    "precision" double precision,
    id_cliente numeric,
    estado numeric DEFAULT 0,
    fecha_check_out timestamp without time zone,
    detalle character varying(1000)
);


ALTER TABLE public.mx_registro_obvii OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 68050)
-- Name: mx_registro_obvii_id_registro_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_registro_obvii_id_registro_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_registro_obvii_id_registro_seq OWNER TO postgres;

--
-- TOC entry 3357 (class 0 OID 0)
-- Dependencies: 205
-- Name: mx_registro_obvii_id_registro_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_registro_obvii_id_registro_seq OWNED BY mx_registro_obvii.id_registro;


--
-- TOC entry 210 (class 1259 OID 68100)
-- Name: mx_registro_usuario_locate; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_registro_usuario_locate (
    id_reg_usuario integer NOT NULL,
    id_usuario numeric,
    fecha timestamp without time zone,
    estado numeric,
    usuario character varying(300),
    clave character varying(300),
    id_cliente numeric
);


ALTER TABLE public.mx_registro_usuario_locate OWNER TO postgres;

--
-- TOC entry 3358 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN mx_registro_usuario_locate.estado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN mx_registro_usuario_locate.estado IS '0=exitoso
1=no exitoso';


--
-- TOC entry 209 (class 1259 OID 68098)
-- Name: mx_registro_usuario_locate_id_reg_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_registro_usuario_locate_id_reg_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_registro_usuario_locate_id_reg_usuario_seq OWNER TO postgres;

--
-- TOC entry 3359 (class 0 OID 0)
-- Dependencies: 209
-- Name: mx_registro_usuario_locate_id_reg_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_registro_usuario_locate_id_reg_usuario_seq OWNED BY mx_registro_usuario_locate.id_reg_usuario;


--
-- TOC entry 208 (class 1259 OID 68079)
-- Name: mx_usuarios_obvii; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE mx_usuarios_obvii (
    id_usuario integer NOT NULL,
    nombre character varying(400),
    id_cliente numeric,
    apellido character varying(400),
    fecha_registro time without time zone,
    mail character varying(400),
    clave character varying(10),
    estado numeric,
    tipo_usuario numeric DEFAULT 0,
    demo boolean DEFAULT true,
    app numeric
);


ALTER TABLE public.mx_usuarios_obvii OWNER TO postgres;

--
-- TOC entry 3360 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN mx_usuarios_obvii.estado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN mx_usuarios_obvii.estado IS '0=activo
1=inactivo';


--
-- TOC entry 3361 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN mx_usuarios_obvii.app; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN mx_usuarios_obvii.app IS '0=clasica
1=clasica+checkout
2= 1+ texto
3=2+ imagen
';


--
-- TOC entry 207 (class 1259 OID 68077)
-- Name: mx_usuarios_obvii_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mx_usuarios_obvii_id_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mx_usuarios_obvii_id_usuario_seq OWNER TO postgres;

--
-- TOC entry 3362 (class 0 OID 0)
-- Dependencies: 207
-- Name: mx_usuarios_obvii_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mx_usuarios_obvii_id_usuario_seq OWNED BY mx_usuarios_obvii.id_usuario;


--
-- TOC entry 3302 (class 2604 OID 101324)
-- Name: id_agenda; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_agenda_obvii ALTER COLUMN id_agenda SET DEFAULT nextval('mx_agenda_obvii_id_agenda_seq'::regclass);


--
-- TOC entry 3301 (class 2604 OID 84498)
-- Name: id_archivo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_archivos_obvii ALTER COLUMN id_archivo SET DEFAULT nextval('mx_archivos_obvii_id_archivo_seq'::regclass);


--
-- TOC entry 3303 (class 2604 OID 101333)
-- Name: id_categoria_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_categoria_usuario_obvii ALTER COLUMN id_categoria_usuario SET DEFAULT nextval('mx_categoria_usuario_obvii_id_categoria_usuario_seq'::regclass);


--
-- TOC entry 3292 (class 2604 OID 51543)
-- Name: id_cliente; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_cliente ALTER COLUMN id_cliente SET DEFAULT nextval('mx_cliente_id_cliente_seq'::regclass);


--
-- TOC entry 3293 (class 2604 OID 51573)
-- Name: id_empresa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_empresa ALTER COLUMN id_empresa SET DEFAULT nextval('mx_empresa_id_empresa_seq'::regclass);


--
-- TOC entry 3305 (class 2604 OID 117977)
-- Name: id_det_prod; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_producto_detalle_obvii ALTER COLUMN id_det_prod SET DEFAULT nextval('mx_check_producto_detalle_id_det_prod_seq'::regclass);


--
-- TOC entry 3304 (class 2604 OID 117968)
-- Name: id_producto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_producto_obvii ALTER COLUMN id_producto SET DEFAULT nextval('mx_check_producto_id_producto_seq'::regclass);


--
-- TOC entry 3295 (class 2604 OID 68055)
-- Name: id_registro; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_registro_obvii ALTER COLUMN id_registro SET DEFAULT nextval('mx_registro_obvii_id_registro_seq'::regclass);


--
-- TOC entry 3300 (class 2604 OID 68103)
-- Name: id_reg_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_registro_usuario_locate ALTER COLUMN id_reg_usuario SET DEFAULT nextval('mx_registro_usuario_locate_id_reg_usuario_seq'::regclass);


--
-- TOC entry 3297 (class 2604 OID 68082)
-- Name: id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mx_usuarios_obvii ALTER COLUMN id_usuario SET DEFAULT nextval('mx_usuarios_obvii_id_usuario_seq'::regclass);


-- Completed on 2014-09-08 15:24:06

--
-- PostgreSQL database dump complete
--

