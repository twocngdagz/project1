@extends('site.layouts.default')

{{-- Title --}}
@section('title')
<!--
{{{ Lang::get('user/user.login') }}} ::
-->
@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('referralgrid') }}
<div class="page-header">
<!--- buttons bar --->
    <div class="row form-inline">
<!--- search --->
      <div class="col-md-3">
        <div id="custom-search-input col-md-3">
            <div class="input-group">
                <input type="text" class="  search-query form-control" placeholder="Search" />
                <span class="input-group-btn">
                    <button class="btn btn-info" type="button">
                        <span class=" glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </div>
       </div>    
<!--- /search --->
        <div class="col-md-7">
             <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseOne">New Referral</button>
            
            <span class="myDropdownCheckboxBasic dropdown-checkbox dropdown">
            <a class="dropdown-checkbox-toggle btn btn-default" data-toggle="dropdown" href="#">Columns<b class="caret"></b></a>
            <div class="dropdown-checkbox-content">
                <ul class="dropdown-checkbox-menu">
                    <li><div class="layout"><input type="checkbox" id="259156031761.2378" checked="checked"><label for="259156031761.2378">Forever.</label></div></li>
                    <li><div class="layout"><input type="checkbox" id="151622478823.54007" checked="checked"><label for="151622478823.54007">Gooooooooooogle</label></div></li>
                    <li><div class="layout"><input type="checkbox" id="192750892704.64886"><label for="192750892704.64886">NO WAY!</label></div></li><li><div class="layout"><input type="checkbox" id="598940753587.1288" checked="checked"><label for="598940753587.1288">2 times?</label></div></li>
                </ul>
            </div>
            </span>

                    
              <span class="myDropdownCheckboxBasic dropdown-checkbox dropdown">
            <a class="dropdown-checkbox-toggle btn btn-default" data-toggle="dropdown" href="#">Filters<b class="caret"></b></a>
            <div class="dropdown-checkbox-content">
                <ul class="dropdown-checkbox-menu">
                    <li><div class="layout"><input type="checkbox" id="259156031761.2378" checked="checked"><label for="259156031761.2378">Forever.</label></div></li>
                    <li><div class="layout"><input type="checkbox" id="674542327403.4232" checked="checked"><label for="674542327403.4232">I love cheese.</label></div></li>
                    <li><div class="layout"><input type="checkbox" id="1236373319835.3083"><label for="1236373319835.3083">Meat for all</label></div></li>
                    <li><div class="layout"><input type="checkbox" id="362868609729.725" checked="checked"><label for="362868609729.725">Horse</label></div></li>
                    <li><div class="layout"><input type="checkbox" id="151622478823.54007" checked="checked"><label for="151622478823.54007">Gooooooooooogle</label></div></li>
                    <li><div class="layout"><input type="checkbox" id="192750892704.64886"><label for="192750892704.64886">NO WAY!</label></div></li><li><div class="layout"><input type="checkbox" id="598940753587.1288" checked="checked"><label for="598940753587.1288">2 times?</label></div></li>
                </ul>
            </div>
            </span>
        </div>

    </div>
</div>
<!--- /buttons bar --->

<!--- tables --->
<div class="row">
    <div class="col-md-12">
                <table class="table table-bordered table-striped" style="text-align:center">
                    <th style="text-align:center">Name</th>
                    <th style="text-align:center">Jan</th>
                    <th style="text-align:center">Feb</th>
                    <th style="text-align:center">Mar</th>
                    <th style="text-align:center">Apr</th>
                    <th style="text-align:center">May</th>
                    <th style="text-align:center">Jun</th>
                    <th style="text-align:center">Jul</th>
                    <th style="text-align:center">Aug</th>
                    <th style="text-align:center">Sep</th>
                    <th style="text-align:center">Oct</th>
                    <th style="text-align:center">Nov</th>
                    <th style="text-align:center">Dec</th>
                    <th style="text-align:center">YTD</th>
                    
                    <tr>
                        <td>Direct Access</td>
                        <td>23</td>
                        <td>3</td>
                        <td>44</td>
                        <td>2</td>
                        <td>12</td>
                        <td>33</td>
                        <td>33</td>
                        <td>33</td>
                        <td>5</td>
                        <td>65</td>
                        <td>8</td>
                        <td>3</td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Dr.Nobody </td>
                        <td>123</td>
                        <td>3</td>
                        <td>44</td>
                        <td>87</td>
                        <td>4</td>
                        <td>23</td>
                        <td>76</td>
                        <td>3</td>
                        <td>77</td>
                        <td>45</td>
                        <td>90</td>
                        <td>5</td>
                        <td>500</td>
                    </tr>
                    
                </table>
    </div>
</div>
<!--- /tables --->

    </fieldset>
</form>

@stop
