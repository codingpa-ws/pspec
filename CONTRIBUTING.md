# Contributing to PSpec

PSpec is an experimental testing framework and still needs a lot of work.

While its basics work from my initial over-the-weekend prototype, if you
want to help me get this project into a more usable state, I’m happy
about [bug reports](#reporting-bugs),
[feature requests & feedback](#feature-requests--feedback) and
[code contributions](#contributing-code). 🧡

## Reporting bugs

This project is still experimental and should likely not used where its
critical that tests work as you’d expect. 😉

If you encouter a bug, please check if it has
[already been reported][gli] and if not, please
[create a bug report][bug].

[gli]: https://gitlab.com/codingpaws/pspec/-/issues?sort=created_date&state=opened&label_name[]=bug
[bug]: https://gitlab.com/codingpaws/pspec/-/issues/new?issue%5Bmilestone_id%5D=

## Feature requests & feedback

Create a [new issue][bug] to leave feedback or to request a feature.

## Contributing code

To contribute code, you should have PHP 8.0 or later installed. If you
don’t, [install it from php.net][phpnet]. You need a recent version of
composer (the PHP package manager) too. Download it from
[getcomposer.org][composer] or execute `composer self-update` to update
an existing composer installation.

[phpnet]: https://www.php.net/manual/en/install.php
[composer]: https://getcomposer.org/download/

### Clone the project

Head to the [project overview page][project], click on the **Clone**
button, and copy the SSH or HTTPS clone URL.

Use `git clone` to clone the project to a local folder.

[project]: https://gitlab.com/codingpaws/pspec

### Install dependencies

Open a terminal in the newly created folder and use `composer install` to
install all dependencies and set up the project.

### Make your changes

All framework code is located in the `src` directory. That code is tested
in the `spec` directory and general examples of tests are in the
`examples` directory.

Checkout a new branch, make your changes, and push the branch to GitLab.

You can check that all tests pass by running `src/bin/pspec`. Keep in
mind that PSpec tests _itself_ using your local version of PSpec.

Once PSpec is released, there will be a continuous integration job that
runs all tests using the latest release of PSpec. Read more in [#12][12].

[12]: https://gitlab.com/codingpaws/pspec/-/issues/12
