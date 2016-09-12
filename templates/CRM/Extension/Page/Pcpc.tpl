<h3>Personal Campaign Pages</h3>

{if $rows}
<div id="ltype">
<p></p>
{include file="CRM/common/pager.tpl" location="top"}
{include file="CRM/common/pagerAToZ.tpl"}
{include file="CRM/common/jsortable.tpl"}
{strip}
<table id="options" class="display">
  <thead>
    <tr>
    <th>{ts}Page Title{/ts}</th>
    <th>{ts}Contribution Page / Event{/ts}</th>
    <th>{ts}# Contributions{/ts}</th>
    <th>{ts}Amount Raised{/ts}</th>
    <th>{ts}Targed Amount{/ts}</th>       
    <th>{ts}Status{/ts}</th>       
    <th></th
    </tr>
  </thead>
  <tbody>
  {foreach from=$rows item=row}
  <tr id="row_{$row.page_id}" class="{$row.class}">
  <td><a href="{crmURL p='civicrm/pcp/info' q="reset=1&id=`$row.page_id`" fe='true'}" title="{ts}View Personal Campaign Page{/ts}" target="_blank">{$row.title}</a></td>
    <td><a href="{crmURL p=$row.url}" title="{ts}View Page{/ts}" target="_blank">{$row.page_title}</a></td>
    <td>{$row.contributors}</td>
   <td>{$row.total}</td> 
   
 <td>{$row.goal_amount}</td>
    
<td>{$row.status_id}</td>
    <td><a href="{crmURL p='civicrm/pcp/info' q="action=update&reset=1&id=`$row.page_id`&context=dashboard" fe='true'}" title="{ts}Edit Personal Campaign Page{/ts}" target="_blank">Edit</a> </td>
  </tr>
  {/foreach}
  </tbody>
</table>
{/strip}
</div>
{else}
<div class="messages status no-popup">
<div class="icon inform-icon"></div>
    {if $isSearch}
        {ts}There are no Personal Campaign Pages which match your search criteria.{/ts}
    {else}
        {ts}There are currently no Personal Campaign Pages.{/ts}
    {/if}
</div>
{/if}

