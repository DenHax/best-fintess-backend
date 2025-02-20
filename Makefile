all:
	run-serv

compose-create-env:
	. ./scripts/env-compose.sh

compose-run:
	. ./scripts/compose-run.sh

compose-build:
	. ./scripts/compose-build.sh

compose-down:
	. ./scripts/compose-down.sh

create-db:
	. ./scripts/create-db-psql.sh

look:
	. ./scripts/lookup.sh

run-serv:
	. ./scripts/php-start.sh

auto-start:
	. scripts/autostart.sh
	. scripts/psql_start.sh
	@$(MAKE) run-serv

conn:
	. ./scripts/psql-conn.sh

compose-autostart:
	@$(MAKE) compose-run
	@$(MAKE) create-db
