app.component('multiCheckboxContainer', {
    controller: function(){
        var ctrl = this;
        var checkboxes = [];
        var checkboxModels = [];
        var previousClickedCheckbox = null;

        ctrl.addCheckbox = addCheckbox;
        ctrl.onCheckboxClick = onCheckboxClick;

        function addCheckbox(checkbox, checkboxModelCtrl) {
            checkboxes.push(checkbox);
            checkboxModels.push(checkboxModelCtrl);
        }

        function onCheckboxClick(checkbox, shiftKey) {
            var start, end, i, checking;
            if (shiftKey && previousClickedCheckbox) {
                checking = checkbox.prop('checked')
                start = checkboxes.indexOf(previousClickedCheckbox);
                end = checkboxes.indexOf(checkbox);
                if (start > end) {
                    start = start + end;
                    end = start - end;
                    start = start - end;
                }
                for (i = start; i <= end; i++) {
                    checkboxes[i].prop('checked', checking);
                    checkboxModels[i].$setViewValue(checking);
                }
            }
            previousClickedCheckbox = checkbox;
        }
    }
});

app.directive('multiCheckbox', function () {
    return {
        restrict: 'A',
        require: ['^^multiCheckboxContainer', 'ngModel'],
        link: function (scope, element, attrs, controllers) {
            var containerCtrl = controllers[0];
            var ngModelCtrl = controllers[1];
            containerCtrl.addCheckbox(element, ngModelCtrl);

            element.on('click', function (ev) {
                containerCtrl.onCheckboxClick(element, ev.shiftKey);
            });
        }
    };
});