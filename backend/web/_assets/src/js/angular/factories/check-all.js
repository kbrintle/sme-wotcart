app.factory("CheckAll",
        ['$filter',
        function($filter){

            function CheckAll(){
                this.isAllSelected          = false;
                this.isAllVisibleSelected   = false;

                this.checkAll = function(collection, filtered_collection){
                    var toggleStatus = this.isAllSelected;

                    angular.forEach(
                        collection,
                        function(record){
                            record.selected = toggleStatus;
                        });

                    if(toggleStatus === false){
                        this.isAllVisibleSelected = false;
                    }
                };

                this.checkAllVisible = function(collection, filtered_collection){
                    var toggleStatus    = this.isAllVisibleSelected;
                    this.isAllSelected  = false;

                    angular.forEach(
                        collection,
                        function(record){
                            record.selected = false;
                        });

                    angular.forEach(
                        filtered_collection,
                        function(record){
                            record.selected = toggleStatus;
                        });
                };

                this.checkItem = function(collection, filtered_collection){
                    this.isAllSelected = collection.every(
                        function(record){
                            return record.selected;
                        });
                    this.isAllVisibleSelected = filtered_collection.every(
                        function(record){
                            return record.selected;
                        });
                    return collection;
                };

            }

            return {
                init: CheckAll
            }

        }
    ]
);