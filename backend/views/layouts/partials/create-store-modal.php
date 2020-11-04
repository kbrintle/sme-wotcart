<!-- Modal -->
<div class="modal fade" id="createStoreModal" tabindex="-1" role="dialog" aria-labelledby="createStoreModalLabel"
    ng-controller="CreateWizardController">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="create-store"
                  ng-submit="create()">
                <div class="modal-body">
                    <div class="step"
                         ng-class="{'active':step.current==1,
                                    'past':step.current==2}">
                        <div class="head">
                            Enter your store name and url below.
                        </div>
                        <div class="content">
                            <div class="row form-group">
                                <div class="col-xs-12">
                                    <label>Store Name</label>
                                    <input type="text" class="form-control"
                                           ng-model="data.name" />
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-xs-12">
                                    <label>URL</label>
                                    <input type="text" class="form-control"
                                           ng-model="data.url"
                                           ng-change="validateUrl()"/>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="step"
                         ng-class="{'active':step.current==2,
                                    'past':step.current==3,
                                    'future':step.current==1}">
                        <div class="head">
                            Assign brands to your store below.
                        </div>
                        <div class="content">
                            <div class="row">
                                <div class="col-xs-6 col-md-3"
                                    ng-repeat="brand in brands"
                                    ng-click="brand.selected=!brand.selected">
                                    <div class="brand-panel"
                                         ng-class="{'active':brand.selected}"
                                         ng-style="{'background-image':'url(/uploads{{brand.logo_color}})'}">
                                        <i class="check material-icons">check_circle</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="step"
                         ng-class="{'active':step.current==3,
                                    'future':step.current==2}">
                        <div class="head">
                            Review your store before creating it.
                        </div>
                        <div class="content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Store Name</h4>
                                    <p>{{data.name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Store URL</h4>
                                    <p>{{data.url}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Brands</h4>
                                    <div class="row">
                                        <div class="col-xs-6 col-md-3"
                                             ng-repeat="brand in brands | filter:{selected:true}">
                                            <div class="brand-panel"
                                                 ng-class="{'active':brand.selected}"
                                                 ng-style="{'background-image':'url(/themes/default/_assets/src/images/{{brand.logo_color}})'}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-transparent"
                        ng-if="step.current==1"
                        ng-click="cancel()">Cancel</button>
                    <button type="button" class="btn btn-transparent"
                        ng-if="step.current>1"
                        ng-click="step.back()">Back</button>
                    <button type="button" class="btn btn-primary"
                        ng-if="step.current<step.max"
                        ng-click="step.forward()">Next</button>
                    <input type="submit" class="btn btn-primary" value="Create"
                        ng-if="step.current==step.max
                                && data.name
                                && data.url" />
                </div>
            </form>
        </div>
    </div>
</div>