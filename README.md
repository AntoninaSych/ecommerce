#### Install Laravel 9.x running on PHP 8.x

1. Clone the repository
   ```bash
   git clone --depth=1 --branch=main git@bitbucket.org:geiger-it/laravel9-quickstart.git
   ```

2. Re-initialize git for your own project
    ```bash
    cd { project_directory }
    rm -rd .git               # if copying from local
    rm -rd .idea/             # if copying from local
    rm -rd .vagrant/          # if copying from local
    git init
    git add --all && git commit -m "inital laravel quickstart clone"
    ```

3. Search and replace instances of `myproject` with your own project name.  Be sure to rename the
   /ansible/roles/**app-myproject** directory as well as the reference to it in playbook-local.yml. Some day, we will
   move all of these roles to their own private repositories. This will allow us to centrally manage roles and
   update them without having to update every project's version of that role.

4. Copy the Vagrantfile, choose a random private IP that won't conflict with your other projects, and **update your ansible vars**
    ```bash
    cp Vagrantfile.example Vagrantfile
    vi Vagrantfile
    ```

5. Drop your id_rsa (ssh private key) into the /provision/private directory. This gives your vm (guest) machine the
   same git access privileges that your host machine has when fetching private repositories.

6. Vagrant up, or start reviewing the provisioner for changes you want to make for your application. While that's running,
   you may want to add a record to you local hosts file (if you'd rather not use an assigned ip address).

7. Replace this readme.md with information about your application and how to run it.

8. Start building your application.

Here are some first steps you might take building your application:

 - Review the `provision/ansible/playbook-local.yml` and `provision/ansible/group_vars/local/vars.yml` files, setting variables and removing anything not needed. 
 - Be sure that personal developer secrets are passed through the Vagrantfile (noting the keys in Vagrantfile.example without real secret information)
 - Shared environment secrets (e.g. an AWS S3 access key) are stored in the environment's group_vars vars.yml. Remember that local, development, or staging environments (and therefore var files) must never contain secret information or live production data.

### Application Checklist

#### ☑ Standard framework and libraries

Get approval before including any 3rd party packages and
libraries. A security audit is necessary to ensure the safety of our applications.


#### ☑ Private information is never present in the repository

This includes passwords AND usernames, hostnames to service endpoints (database, redis, kerberos...), email
addresses, and customer/vendor/employee information.
**Do not** use real-world test data in your Seeds. However, do try to simulate real-world test data scenarios.

To store staging and production variables in a repository, use ansible-vault to encrypt the vars.yml file.  `ansible-vault encrypt provision/ansible/group-vars/qa/vars.yml`  You will be prompted for a password to encrypt the file. Check with a team member or lead for the standard staging encryption password. Production passwords are long, complex, and access is limited to DevOps and Infrastructure roles for security.


#### ☑ Configure robots.txt and protect management/admin endpoints

Internal applications that do not have SEO requirements should block all robots from crawling the application. Private
endpoints must not be added to robots.txt; this is an information disclosure that could lead to targeted attacks.
Instead, configure the http header or use meta tags on private endpoints to block robots.

`X-Robots-Tag: noindex, nofollow`

`<meta name="robots" content="noindex, nofollow">`

Do not use these commonly scanned and attacked target paths: /admin, /test, /phpinfo, /manage, /management


#### ☑ Queue? Redis.

There are many shortcomings to using the database as a queue driver; it's not really built to
handle the types of features we look for in a queue. Be sure your queue messages contain the least amount of data
required for a job to process. Do not rely on serialized models (encoded objects) as part of your message.
This forces the application state into the message; if the job fails because of the state of the Order Model,
requiring a bugfix, you can't easily change the state of the bugged model since it's state is serialized


#### ☑ Disposable and redundant hosts

Ensure that any necessary services ancillary to the application (database,
cache, queue, file storage...) are running externally to the application server in non-development environments. The
application should be ready to scale out to 2 or more instances, or get destroyed and re-created without any data
loss. Take note of how scheduled tasks should be configured when there is more than one application instance
running.  https://laravel.com/docs/9.x/scheduling#running-tasks-on-one-server

#### ☑ Security Audit

When working with systems that handle customer PII (personally identifiable information) or payments (credit card
processing), have an architect review any code changes to the system.

#### ☑ APIs

APIs must be secured, non-public, and require registration/authentication for the implementing application.
Each application and environment must have its own unique authentication key / credentials for access to the
application which must meet high complexity requirements. Application integrations must not be tied to a user -
including a developer account - so that if a user is removed from a system, it will not impact the application
integration.

#### ☑ SEO

Is SEO a concern for the application? robots.txt is configured to block crawling by default.
