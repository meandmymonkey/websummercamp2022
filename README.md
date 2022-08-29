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