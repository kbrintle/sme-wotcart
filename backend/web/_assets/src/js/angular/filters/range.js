app.filter('range', function(){
    return function(records, expression){
        var output = [];

        min = parseInt(expression.min);
        max = parseInt(expression.max);

        if( min || max ){
            angular.forEach(records, function(record){
                if( record[expression.key] ){
                    if( min && max ){
                        if( record[expression.key] >= min
                            && record[expression.key] <= max ){
                            output.push(record);
                        }
                    }
                    if( min && !max ){
                        if( record[expression.key] >= min ){
                            output.push(record);
                        }
                    }
                    if( !min && max ){
                        if( record[expression.key] <= max ){
                            output.push(record);
                        }
                    }
                }
            });

            return output;
        }

        return records;
    };
});