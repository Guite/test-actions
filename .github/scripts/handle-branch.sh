#!/bin/bash

if git rev-parse --verify origin/$GENERATOR_BRANCH > /dev/null 2>&1
then
    echo "Branch $GENERATOR_BRANCH exists"
    git checkout $GENERATOR_BRANCH
    # git merge master
else
    echo "Branch $GENERATOR_BRANCH does not exist"
    git checkout master
    git branch $GENERATOR_BRANCH
    git branch --set-upstream-to $GENERATOR_BRANCH
fi
