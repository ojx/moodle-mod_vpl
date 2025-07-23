#!/bin/bash
# This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
# Script for JavaScript language using NodeJs
# Copyright (C) 2024 Juan Carlos Rodríguez-del-Pino
# License http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
# Author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>

# @vpl_script_description JavaScript with DMOJ i/o functions
# load common script and check programs
. common_script.sh
check_program nodejs
if [ "$1" == "version" ] ; then
	get_program_version -v
fi
# <<(echo "to be prepended") < text.txt | sponge text.txt
get_first_source_file js
cat common_script.sh > vpl_execution
[ "$(command -pv npm)" != "" ] && (echo "export NODE_PATH=$(npm root -g)" >> vpl_execution)

# echo "cp -R /usr/local/lib/cstools/node_modules ./" >> vpl_execution

echo "ln -s /usr/local/lib/cstools/node_modules node_modules" >> vpl_execution

echo "sed -i \"0,/^/s//const _readlineSync = require('readline-sync'); /\" \"$FIRST_SOURCE_FILE\"" >> vpl_execution
echo "echo -e '\nfunction print(){console.log(...arguments);}\nfunction quit(){process.exit();}\nfunction gets(msg){return _readlineSync.question(msg===undefined?\"\":msg+\"\\\\n\");}\nfunction prompt(msg){return _readlineSync.question(msg===undefined?\"\":msg+\"\\\\n\");}\nfunction flush(){process.stdout.write(\"\");}\nfunction write(buffer){let binary=\"\";for(let _i=0;_i<buffer.byteLength;_i++){binary+=\"\"+buffer[_i];}process.stdout.write(binary);}\nfunction read(bytes){let buffer=new ArrayBuffer(bytes);let _i=0;while(_i<bytes){buffer[_i++]=_readlineSync.keyIn(\"\",{hideEchoBack:true,mask:\"\"});}return buffer;}\n' >> \"$FIRST_SOURCE_FILE\"" >> vpl_execution
# echo "cat \"$FIRST_SOURCE_FILE\"" >> vpl_execution
echo "nodejs --require readline-sync \"$FIRST_SOURCE_FILE\" \$@" >> vpl_execution

chmod +x vpl_execution
apply_run_mode