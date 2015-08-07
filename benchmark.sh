#!/bin/sh

base="http://127.0.0.1/php-framework-benchmark"

cd `dirname $0`

. ./list.sh

cd benchmarks

if [ "$1" = "hello" ] || [ "$1" = "all" ] || [ $# -eq 0 ]; then
         export targets="$list"
	sh hello_world.sh "$base" "hello"
	php ../bin/show_results_table.php "hello"
fi

if [ "$1" = "orm" ] || [ "$1" = "all" ]; then
	export targets="$orm"
	sh hello_world.sh "$base" "orm"
	php ../bin/show_results_table.php "orm"
fi

if [ "$1" = "select" ] || [ "$1" = "all" ]; then
	export targets="$select"
	sh hello_world.sh "$base" "select"
	php ../bin/show_results_table.php "select"
fi

if [ "$1" = "gcms" ] || [ "$1" = "all" ]; then
	export targets="$gcms"
	sh hello_world.sh "$base" "gcms"
	php ../bin/show_results_table.php "gcms"
fi