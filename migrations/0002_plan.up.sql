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
