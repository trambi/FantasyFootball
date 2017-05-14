#! /bin/bash

function usage(){
  echo "${0} start"
  echo "Set up the directory to be used on webserver"
}

if [[ ${1} == "start" ]]
then
  rm -fr ".git"
  rm -fr ".gitignore"
  rm -fr "TournamentAdminBundleMeta"
  rm -fr "TournamentCoreBundleMeta"
  rm -fr "LICENSE"
  rm -fr "README.md"
  mv "composer.json" ../../
  chown -R www-data:www-data "TournamentCoreBundle"
  chown -R www-data:www-data "TournamentAdminBundle"
  chown -R www-data:www-data "UserBundle"
else
  usage
fi
