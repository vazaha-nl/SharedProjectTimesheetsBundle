# Shared Project Timesheets Bundle

**THIS IS A CLONE OF https://github.com/dexterity42/SharedProjectTimesheetsBundle**

The original author seems to have abandoned the plugin and is not responding.
Due to popular demand it was forked and extended and **IS working with Kimai 2.0+**.

----

You can share your project report (budgets, timesheets) with anyone by a public URL and optional password.

## Features

- Create publicly accessible URL for a project
- Access control feature
  - protect the shared project timesheets with a password
- View control feature
  - show or hide user of records (name of user)
  - show or hide rates of records (hour rate, total rate)
  - show or hide chart with day comparison by selected month
  - show or hide chart with month comparison by selected year
- View customizations
  - define whether and how to merge records of a day (e.g. merge records of one day, use description of last record)

## Installation

This plugin is compatible with the following Kimai releases:

| Bundle version | Minimum Kimai version |
|----------------|-----------------------|
| 3.0.0          | 2.0.26                |

First clone this plugin to your Kimai installation `plugins` directory:
```
cd /kimai/var/plugins/
git clone https://github.com/Keleo/SharedProjectTimesheetsBundle.git
```

Go back to the root of your Kimai installation and clear the cache:
```
cd /kimai/
bin/console cache:clear
bin/console cache:warmup
```

Execute database migrations:
```
bin/console kimai:bundle:shared-project-timesheets:install
```

You're done. Open up your browser and navigate to "Applications > Shared project timesheets".

## Permissions

The permission `shared_projects` is required to manage the "shared project timesheets", which is assigned to the role `ROLE_SUPER_ADMIN` by default.
