#!/bin/bash
# This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
# Script for running Java language
# Copyright (C) 2015 onwards Juan Carlos Rodríguez-del-Pino
# License http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
# Author Juan Carlos Rodriguez-del-Pino

function getClassName {
    #replace / for .
	local CLASSNAME=$(echo "$1" |sed 's/\//\./g')
	#remove file extension .java
	CLASSNAME=$(basename "$CLASSNAME" .java)
	echo $CLASSNAME
}
function getClassFile {
	#remove file extension .java
	local CLASSNAME=$(basename "$1" .java)
	local DIRNAME=$(dirname "$1")
	echo "$DIRNAME/$CLASSNAME.class"
}
function hasMain {
	local FILE=$(getClassFile "$1")

	if [ -f "$FILE" ] ; then
	  	#remove file extension .class
      #if [ "$FILE" = "./Sample.class" ] ; then
        # cat -v $FILE
      #fi

	  	local CLSNAME=$(basename "$FILE" .class)

	    #echo $FILE
	  	# echo $CLSNAME

	    # CHANGED!
	    # cat -v $FILE #   - this to see .class file contents
	    # regex for main launch()   \^A\^@\^DMain\^A\^@\^Flaunch\^A\^@\^V\(\[Ljava/lang/String;\)
	    # WAS: cat -v "$FILE" | grep -E "\^A\^@\^Dmain\^A\^@\^V\(\[Ljava/lang/String;\)" &> /dev/null

      # cat -v "$FILE" | grep -E "\^A\^@\^DMain\^A\^@\^Flaunch\^A\^@\^V\(\[Ljava/lang/String;\)|\^A\^@\^Dmain\^A\^@\^V\(\[Ljava/lang/String;\)" &> /dev/null
	    # cat -v "$FILE" | grep -E "\^A\^@\^FSample\^A\^@\^Flaunch\^A\^@\^V\(\[Ljava/lang/String;\)|\^A\^@\^Dmain\^A\^@\^V\(\[Ljava/lang/String;\)" &> /dev/null

	    # cat -v "$FILE" | grep -E "\^A\^@\^.${CLSNAME}\^A\^@\^Flaunch\^A\^@\^V\(\[Ljava/lang/String;\)|\^A\^@\^Dmain\^A\^@\^V\(\[Ljava/lang/String;\)" &> /dev/null
	    cat -v "$FILE" | grep -E "\^A\^@\^Flaunch\^A\^@\^V\(\[Ljava/lang/String;\)|\^A\^@\^Dmain\^A\^@\^V\(\[Ljava/lang/String;\)" &> /dev/null
	else
		return 1
	fi
}

# @vpl_script_description Default javac with linked JavaFx libraries
# load common script and check programs
. common_script.sh

check_program javac
if [ "$1" == "version" ] ; then
	get_program_version -version
fi

check_program java

JUNIT4=/usr/share/java/junit4.jar
if [ -f $JUNIT4 ] ; then
	CLASSPATH=$CLASSPATH:$JUNIT4
fi
get_source_files jar NOERROR
for JARFILE in $SOURCE_FILES
do
	CLASSPATH=$CLASSPATH:$JARFILE
done

# ADDED!
CLASSPATH=$CLASSPATH:/usr/share/openjfx/lib/*

export CLASSPATH

get_source_files java
# compile all .java files

javac -Xlint:deprecation $2 $SOURCE_FILES
if [ "$?" -ne "0" ] ; then
	echo "Not compiled"
 	exit 0
fi
# Search main procedure class
MAINCLASS=
for FILENAME in $VPL_SUBFILES
do
  # echo $FILENAME
	hasMain "$FILENAME"
	if [ "$?" -eq "0" ]	; then
		MAINCLASS=$(getClassName "$FILENAME")
		break
	fi
done
if [ "$MAINCLASS" = "" ] ; then
	for FILENAME in $SOURCE_FILES
	do
	  # echo $FILENAME
		hasMain "$FILENAME"
		if [ "$?" -eq "0" ]	; then
			MAINCLASS=$(getClassName "$FILENAME")
			break
		fi
	done
fi
# If not main procedure then search for junit4 test classes
if [ "$MAINCLASS" = "" ] ; then
	TESTCLASS=
	for FILENAME in $SOURCE_FILES
	do
		CLASSFILE=$(getClassFile "$FILENAME")
		grep "org/junit/" $CLASSFILE &> /dev/null
		if [ "$?" -eq "0" ]	; then
			TESTCLASS=$(getClassName "$FILENAME")
			break
		fi
	done
	# If no main and no test class then stop
	if [ "$TESTCLASS" = "" ] ; then
		echo "Class with \"public static void main(String[] arg)\" method not found"
		exit 0
	fi
fi

cat common_script.sh > vpl_execution
echo "export CLASSPATH=$CLASSPATH" >> vpl_execution
if [ ! "$MAINCLASS" = "" ] ; then
	# CHANGED!
    # Previous also had this flag:  -Xmx256M

	echo "java -Dprism.order=sw -enableassertions --module-path /usr/share/openjfx/lib --add-modules javafx.controls,javafx.graphics,javafx.fxml $MAINCLASS \$@" >> vpl_execution
else
	echo "java org.junit.runner.JUnitCore $TESTCLASS \$@" >> vpl_execution
fi
chmod +x vpl_execution
for FILENAME in $SOURCE_FILES
do
	CLASSFILE=$(getClassFile "$FILENAME")
	grep -E "javax/swing/(JFrame|JDialog|JOptionPane|JApplet)" $CLASSFILE &> /dev/null
	if [ "$?" -eq "0" ]	; then
		mv vpl_execution vpl_wexecution
		break
	fi
	grep -E "javafx/application/(Application|Scene)" $CLASSFILE &> /dev/null
	if [ "$?" -eq "0" ]	; then
		mv vpl_execution vpl_wexecution
		break
	fi
done
apply_run_mode