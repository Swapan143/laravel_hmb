@extends('admin.layouts.master.master')


@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Permission Add</h2>
        
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="post" action="{{url('admin/role/permission-update')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{$roles->id}}" name="roleId">
                        <div class="table-responsive">
                            <table class="table table-hover" id="package-table">
                                <thead>
                                    <tr>                      
                                        <th>Module</th>     
                                        <th>Permission</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($modules as $key=>$row)
                                    <?php
                                        $sub_modules=$row['sub_modules'];
                                    ?>
                                    <tr>
                                        <td>
                                            @if($row['module']->parent_id=='0')
                                            <b>{{@$row['module']->module_name}}</b>
        
                                            @endif    
                                        </td>
                                        <td>
                                            <input type="checkbox" <?php  if(in_array($row['module']->id,$permission_array)) { echo "checked"; }else{ echo""; } ?> name="module_id[]" value="{{$row['module']->id}}" style="left : 0px !important; opacity:1 !important; position: unset !important;">
                                    
                                        </td>
                                        
                                    </tr>
        
                                    <?php 
                                        if(!empty($sub_modules) && sizeof($sub_modules)> 0)
                                        {
                                            foreach($sub_modules as $sub_module)
                                            {
                                                ?>
                                                <tr>
                                                    <td>
                                                        {{@$sub_module->module_name}}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" <?php  if(in_array($sub_module->id,$permission_array)) { echo "checked"; }else{ echo""; } ?> name="module_id[]" value="{{$sub_module->id}}" style="left : 0px !important; opacity:1 !important; position: unset !important;">
                                                
                                                    </td>
                                                    
                                                </tr>
        
                                                <?php 
                                            } 
                                        }  
                                    ?>            
        
        
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        

                        <div class="row" style="display: flex; justify-content: center">
                            <div class="form-group col-md-12" style="display: flex; justify-content: center">
                                <a class="btn btn-info-back float-right mr-1" href="{{url('admin/role')}}">Back to List</a>
                                <button class="btn btn-info btn-banner float-right" type="submit"></i>Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() 
    {
        $("#meta_form").validationEngine();
    })
    
</script>
@endsection
