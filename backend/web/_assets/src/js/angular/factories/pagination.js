app.factory("Pagination",
    ['$filter',
        function($filter){
            var Pagination = {
                data: {
                    perPage:20,
                    count: 0,
                    currentPage: 0,
                    paginationSet: [],
                    currentData: [],
                    data: [],
                    pagesMin : true,
                    pages: 0,
                    pagesMax : false
                },
                init: function(data, perPage){
                    var $this = this;

                    $this.data.perPage = perPage ? perPage : 20;
                    $this.data.data = data;
                    $this.data.currentData = data;
                    $this.data.count = data.length;
                    $this.goToPage(1);
                },
                update: function(){

                },

                /**
                 * @return updates paginationSet and currentPage
                 * @return triggers $this.buildButtons
                 */
                goToPage: function(index){
                    var $this = this;

                    var pageStart = ($this.data.perPage * index) - $this.data.perPage;
                    var pageEnd = $this.data.perPage * index;

                    $this.data.currentPage = index;
                    $this.data.paginationSet = $this.data.currentData.slice(pageStart, pageEnd);

                    $this.buildButtons();
                },

                getNextPage: function(){
                    var $this = this;

                    var output = [];

                    if( $this.data.currentPage < ($this.data.pages.length - 1) ){
                        output.push( $this.data.currentPage + 1 );
                    }
                    if( $this.data.currentPage < ($this.data.pages.length - 2) ){
                        output.push( $this.data.currentPage + 2 );
                    }

                    return output;
                },

                getPrevPage: function(){
                    var $this = this;

                    var output = [];

                    if( $this.data.currentPage > 3 ){
                        output.push( $this.data.currentPage - 2 );
                    }

                    if( $this.data.currentPage > 2 ){
                        output.push( $this.data.currentPage - 1 );
                    }

                    return output;
                },


                /**
                 * @param: sortOptions @optional object
                 *  {
				     *  	type : value,
				     *  	reverse : boolean
				     *  }
                 *
                 * @param: filterOptions @optional object
                 * 	{
				     * 		filterName : filterValue,
				     * 		...
				     *  }
                 */
                updateSort: function(sortOptions, filterOptions){
                    var $this = this;

                    $this.data.currentData = $filter('orderBy')($this.data.data, sortOptions.type, sortOptions.reverse);

                    angular.forEach(filterOptions, function(v, k){
                        if( v.expression ){ //don't filter if there is no expression. it freaks angular out
                            $this.data.currentData = $filter(v.type)($this.data.currentData, v.expression, (v.strict ? v.strict : false));
                        }
                    });

                    $this.goToPage(1);
                },


                /**
                 * @return updates pagesMin, pages, pagesCurrent, and pagesMax
                 */
                buildButtons: function(){
                    var $this = this;

                    $this.data.pages = Math.ceil( ($this.data.currentData.length / $this.data.perPage) );
                    if($this.data.currentPage <= 1)
                        $this.data.pagesMin = true;
                    else
                        $this.data.pagesMin = false;

                    if($this.data.currentPage >= $this.data.pages)
                        $this.data.pagesMax = true;
                    else
                        $this.data.pagesMax = false;

                    var pageArray = [];
                    for(var i=0; i<$this.data.pages; i++){
                        pageArray.push(i+1);
                    }
                    $this.data.pages = pageArray;
                }
            };

            return Pagination;
        }
    ]
);