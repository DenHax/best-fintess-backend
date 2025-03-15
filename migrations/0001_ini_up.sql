CREATE TABLE Role (
  role_id SERIAL,
  role_name VARCHAR(20) NOT NULL,
  role_description TEXT NOT NULL,
  CONSTRAINT PK_ROLE PRIMARY KEY(role_id)
);

CREATE TABLE client (
  client_uuid UUID DEFAULT gen_random_uuid(),
  client_surname VARCHAR(32) NOT NULL,
  client_firstname VARCHAR(32) NOT NULL,
  client_gender VARCHAR(16) NOT NULL,
  client_age INTEGER NOT NULL,
  client_height INTEGER NOT NULL,
  client_weight INTEGER NOT NULL,
  client_phone VARCHAR(17) NOT NULL,
  client_hash_password VARCHAR(60) NOT NULL,
  client_avatar_path VARCHAR(255) NOT NULL,
  CONSTRAINT PK_CLIENT PRIMARY KEY(client_uuid)
);

CREATE UNIQUE INDEX CLIENT_SURNAME_INDEX on client (
 client_surname
);

CREATE UNIQUE INDEX CLIENT_PK_INDEX on client (
 client_uuid
);


CREATE TABLE CLIENT_ROLE (
  client_id UUID NOT NULL,
  role_id INTEGER NOT NULL,
  PRIMARY KEY (client_id, role_id),
  FOREIGN KEY(client_id) REFERENCES client(client_uuid),
  FOREIGN KEY(role_id) REFERENCES Role(role_id)
);

CREATE TABLE Plan (
  plan_uuid UUID DEFAULT gen_random_uuid(),
  plan_trainer_id UUID,
  plan_cost MONEY NOT NULL,
  plan_name VARCHAR(32) NOT NULL,
  plan_duration INTEGER NOT NULL,
  PRIMARY KEY(plan_uuid),
  FOREIGN KEY(plan_trainer_id) REFERENCES client(client_uuid)
);

CREATE UNIQUE INDEX PLAN_PK_INDEX ON Plan (plan_uuid);

CREATE TABLE Client_Plan (
  client_id UUID NOT NULL,
  plan_id UUID NOT NULL,
  PRIMARY KEY (client_id, plan_id),
  FOREIGN KEY(client_id) REFERENCES client(client_uuid),
  FOREIGN KEY(plan_id) REFERENCES Plan(plan_uuid)
);


