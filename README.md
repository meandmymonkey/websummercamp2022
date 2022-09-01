## Web Summer Camp 2022

# Asynchronous Applications with Symfony

### What is this?

A temporary repository containing material for participants of the workshop
[Asynchronous Applications with Symfony](https://2022.websummercamp.com/asynchronous-applications-with-symfony)
at [Web Summer Camp 2022](https://2022.websummercamp.com/).

### Basic requirements:

#### Hardware: A portable computer

You can (and should!) code along in this workshop. For this, you will
obviously need a laptop computer with your favourite (PHP) development
environment setup. I recommend Linux or macOS. There is no reason why Windows
should not work as well, I just won't be able to answer questions specific
to Docker or PHP on Windows.

#### Knowledge: Docker on your OS

You should feel comfortable using Docker and Docker Compose for local
development. Most of the workshop steps involving docker will be automated,
but when in doubt, you might be required to start and stop containers manually,
and possibly edit port mappings or volumes.

#### Knowledge: PHP

We are going to use PHP 8.1 with some features only available from this
version onward. If you have only used PHP up to 8.0, you will still be fine in
the workshop - but it won't hurt to read up on Readonly Properties, Enums, and
Attributes.

#### Knowledge: Symfony

This workshop will, among other topics, teach you how to use the Symfony
Messenger component. I also will be able to help with questions and problems
concerning the workshop app itself.

However, we will not be able to spend time learning Symfony basics. You should
bring basic working knowledge of a generic Symfony application. Where is
everything, how to configure basic services, etc. No rocket science, but you
should have setup and used the framework before.

#### Software: REST Client

A REST client like Postman (https://www.postman.com/downloads/) or HTTPie
(https://httpie.io/) is not strictly required, but will make things easier to
use. When using the provided PHP container, `httpie` is already installed.

### Workshop Preparation

We are going to use Docker to run some local infrastructure for this workshop.

To minimize setup time during the workshop, and to save the conference network
from huge downloads, please make sure you prepare the following steps before
attending the workshop - better yet, before even travelling to the conference:

- Install a current version of Docker and Docker Compose
- Clone this repository (https://github.com/meandmymonkey/ws-messenger-2022)
- Run `docker-compose up`
- You can stop the environment using `docker-compose stop` once everything is pulled and running

#### Optional: when using macOS

You can improve performance on macOS by working with PHP locally, and running
only the infrastructure in Docker. **This is entirely optional.**

If you want to do so, you will need some stuff in addition to Docker and Docker Compose:

- A local PHP 8.1 including `ext-amqp`, `ext-json`, `ext-iconv`, `ext-pcntl`, `ext-ctype`
- Composer (https://getcomposer.org/)
- The Symfony CLI (https://symfony.com/download)

_________________

# Exercises

**Too challenging?** All things we do during the workshop will be pushed to this repository so you can catch up after every challenge.

**Bored, too easy?** Take a look at the optional challenges and the free-form challenges in the last part of this section and go nuts.

## Challenge 0 (Warmup)

Write a command that sends a "Ping" and a handler that receives and logs it. Try synchronous & asynchronous setups.

## Challenge 1

**Import Aircraft.** Use the `AircraftCsvReader` service to retrieve records of raw data and send them into a queue from a new Symfony command you write.

Write a handler that consumes the messages, converts the data in to `Aircraft` using the `AircraftReader` service. Pass them to the `AircraftUpdater`.

**Import Airports.** Same procedure as for aircraft.

**Optional:** Try out different counts of workers. Try out different sizes of batches. What is the fastest setup?

## Challenge 2

Write a command pulling in transponder data every 10 seconds. Send the data into a queue.

Write a handler receiving the data and log them.

## Challenge 3

Change the handler from the last challenge: Feed the transponder data into the `TransponderStatusUpdater` and log the resulting `TrafficEvent`s.

## Challenge 4

Push the TrafficEvents into yet another queue and turn them into notifications AND into a log file, in two separate handlers.

## Challenge n+1 (things to try if you're bored, in no particular order)

- Refactor the command pulling the transponder updates into a message handler. Trigger it with a pulse event (message) from a new command. Make the message self-destruct when not consumed to prevent piling up.
- Use the live API instead of recorded data. Steps: Set `TRANSPONDER_URL` to your personal url with HTTP Basic Auth, and run the app in the `prod` environment.
- Use the Symfony Lock component to secure imports, pulse command etc. from running in parallel. 
- Get creative with the `App\AirTraffic\Domain\TransponderStatusChecker` and `App\AirTraffic\DomainTrafficEvent` interfaces - extend the application to display other types of traffic events, like close passes, extreme height changes, etc.
- Use a fanout exchange to use the pulse event for multiple tasks (like re-triggering Aircraft and Airport imports on a schedule).
- Use supervisord and docker to setup a new "consumer container" running several workers.
- Create a reference ID when pulling transponder data and attach it to all messages using a custom stamp - log their complete lifecycle.
- Send emails on touchdowns at selected airports (using Symfony Notifier or directly using the Mailer).

_________________

# Class Reference

Don't worry, everything in this project not directly concerned with async operations
comes pre-built! All required services are autowired by Symfony and are ready
to use. Here is a reference:

## Core Services to interact with

`App\AirTraffic\DataImport\TransponderStatusReader`: Pulls in the latest
transponder data. Gives you a collection of new `TransponderStatus`.

`App\AirTraffic\TransponderStatusUpdater`: Takes one or more `TransponderStatus`
and yields `TrafficEvent`s (like takeoffs and touchdowns).

## Importing static data

`App\AirTraffic\AircraftReader` and `App\AirTraffic\AirportReader` Accept arrays of
raw data and convert them to `Aircraft` and `Airport`s.

`App\AirTraffic\AircraftUpdater` and `App\AirTraffic\AirportUpdater` store the data
provided by `AircraftReader` and `AirportReader`.

## Traffic participants

All classes implement `TrafficEvent`.

`App\AirTraffic\Domain\Aircraft`: A DTO containing various info about an aircraft.
This is static data we are going to import.

`App\AirTraffic\Domain\Airport`: Same for an airport.
This is static data we are going to import.

`App\AirTraffic\Domain\TransponderStatus`: A DTO for data sent by a flight transponder.
Contains position and metadata. Identified by a unique ICAO24 code, which relates it
to the `Aircraft` above. This is dynamic data we will consume when our app is running.

## Things happening

`App\AirTraffic\Domain\TrafficEvent\NewTransponder`: A new Transponder has entered
our airspace. This DTO contains all the details, including the `Aircraft`.

`App\AirTraffic\Domain\TrafficEvent\Takeoff`: A plane has taken off. Comes with
an `Aircraft` and an `Airport`.

`App\AirTraffic\Domain\TrafficEvent\Touchdown`: A plane has landed. Comes with
an `Aircraft` and an `Airport`.

_________________

# Documentation

- https://opensky-network.org/
- https://openskynetwork.github.io/opensky-api/
- https://symfony.com/doc/current/messenger.html
- https://symfony.com/doc/current/components/messenger.html
- https://symfonycasts.com/screencast/messenger
- https://www.rabbitmq.com/documentation.html

