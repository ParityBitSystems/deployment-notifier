# Deployment Notifier

A work-in-progress tool to notify services of deployments in your infrastructure.

The initial version only supports emailing stakeholders, pinging codebasehq and slack.
This is because of needs with some existing projects.

The aim is to abstract out the methods of determining the changes that have been
deployed and the methods of notifying a deployment, so that they can be interchanged
with custom integrations. This will need extensive reworking to achieve this aim.

## TODO

 - Introduce Guzzle or similar for Clients
 - Consider splitting things off into their own plug in repositories
