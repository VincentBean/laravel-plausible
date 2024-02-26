# Upgrade Guide

This document contains the guides on how to upgrade major versions.

## 1.x > 2.x

### Namespace changed

The namespace has changed from `VincentBean\LaravelPlausible` to `VincentBean\Plausible`.

The `PlausibleEvent` class has been moved to the `Events` namespace: `VincentBean\Plausible\Events\PlausibleEvent`.

You could use this `sed` command to quickly replace them:
```shell
find . -type f -exec sed -i '' 's/VincentBean\\LaravelPlausible\\PlausibleEvent/VincentBean\\Plausible\\Events\\PlausibleEvent/g' {} \;
find . -type f -exec perl -pi -e 's/VincentBean\\LaravelPlausible/VincentBean\\Plausible/g' {} \;
```
> Tested only with OSX
