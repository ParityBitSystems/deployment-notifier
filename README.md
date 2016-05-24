# Deployment Notifier

[![Build Status](https://travis-ci.org/ParityBitSystems/deployment-notifier.svg?branch=master)](https://travis-ci.org/ParityBitSystems/deployment-notifier) [![Coverage Status](https://coveralls.io/repos/github/ParityBitSystems/deployment-notifier/badge.svg?branch=master)](https://coveralls.io/github/ParityBitSystems/deployment-notifier?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ParityBitSystems/deployment-notifier/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ParityBitSystems/deployment-notifier/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/70b28d25-506d-41b4-ba86-45960b75a92a/mini.png)](https://insight.sensiolabs.com/projects/70b28d25-506d-41b4-ba86-45960b75a92a)

A work-in-progress tool to notify services of deployments in your infrastructure.

The initial version only supports emailing stakeholders, pinging codebasehq and slack.
This is because of needs with some existing projects.

The aim is to abstract out the methods of determining the changes that have been
deployed and the methods of notifying a deployment, so that they can be interchanged
with custom integrations. This will need extensive reworking to achieve this aim.

## TODO

 - Introduce Guzzle or similar for Clients
 - Consider splitting things off into their own plug in repositories
