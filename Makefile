.PHONY : help start stop cli pulse consume-heartbeat consume-airports consume-aircraft consume-transponders consume-traffic-events import
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

start: ## Starts docker environment
	docker-compose up -d

stop: ## Stops docker environment
	docker-compose stop

cli: ## Enters the PHP CLI
	docker-compose run --rm php /bin/bash

pulse: ## Runs application heartbeat for 10 minutes
	bin/console app:pulse --time-limit=600

consume-heartbeat: ## Runs heartbeat consumer (triggers transponder data fetches)
	bin/console messenger:consume heartbeat --queues=pulse_transponder_updates

consume-airports: ## Imports queued airport data
	bin/console messenger:consume airports

consume-aircraft: ## Imports queued aircraft data
	bin/console messenger:consume aircraft

consume-transponders: ## Consumes queued transponder data
	bin/console messenger:consume transponders

consume-traffic-events: ## Consumes queued traffic events
	bin/console messenger:consume traffic_events

import: ## Queues CSV data for import
	bin/console app:import:airports
	bin/console app:import:aircraft