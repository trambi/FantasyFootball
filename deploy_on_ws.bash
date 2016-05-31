#! /bin/bash

function usage(){
  echo "${0} start"
  echo "Set up the directory to be used on webserver"
}

if [[ ${1} == "start" ]]
then
  rm -fr ".git"
  rm -fr ".gitignore"
  rm -fr "TournamentAdminBundle"
  rm -fr "TournamentAdminBundleMeta"
  rm -fr "TournamentCoreBundleMeta"
  rm -fr "LICENSE"
  rm -fr "README.md"
  chown -R www-data:www-data "TournamentCoreBundle"
else
  usage
fi
