{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="delete" size="small"}
    <h3>{gt text="Delete block position"}</h3>
</div>

<p class="alert alert-warning">{gt text="Do you really want to delete block position '%s'?" tag1=$position.name|safetext}</p>

<form class="form-horizontal" role="form" action="{modurl modname="Blocks" type="admin" func="deleteposition"}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
    <input type="hidden" name="confirmation" value="1" />
    <input type="hidden" name="pid" value="{$position.pid|safetext}" />
    <fieldset>
        <legend>{gt text="Confirmation prompt"}</legend>
        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                {button class="btn btn-success" __alt="Delete" __title="Delete" __text="Delete"}
                <a class="btn btn-danger" href="{modurl modname=Blocks type=admin func=view}" title="{gt text="Cancel"}">{gt text="Cancel"}</a>
            </div>
        </div>
        </fieldset>
</form>
{adminfooter}