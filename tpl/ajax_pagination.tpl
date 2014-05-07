{assign var="show" value=5}
{assign var="currPage" value=$PAGINATION.currPage}
{assign var="maxPage" value=$PAGINATION.maxPage}

{if $maxPage > 1}
	<ul class="pagination">
	{if $currPage > $show}
		<li class="row">
			<a href="javascript:pagination('{$PAGINATION.action}', {$PAGINATION.currPage}, 1, '{$PAGINATION.callback}');">Первая</a>
		</li>
	{/if}
	{if $currPage > 1}
		<li class="row">
			<a href="javascript:pagination('{$PAGINATION.action}', {$PAGINATION.currPage}, {($currPage-1)}, '{$PAGINATION.callback}');">«</a>
		</li>
	{/if}

	{if $currPage > $show}
		{section name=i start=($currPage-$show) loop=$currPage}
		<li class="row">
			<a href="javascript:pagination('{$PAGINATION.action}', {$PAGINATION.currPage}, {$smarty.section.i.index}, '{$PAGINATION.callback}');">{$smarty.section.i.index}</a>
		</li>
		{/section}
	{else}
		{section name=i start=1 loop=$currPage}
		<li class="row">
			<a href="javascript:pagination('{$PAGINATION.action}', {$PAGINATION.currPage}, {$smarty.section.i.index}, '{$PAGINATION.callback}');">{$smarty.section.i.index}</a>
		</li>
		{/section}
	{/if}

		<li class="row active">
			{$currPage}
		</li>

	{if $maxPage - $currPage + 1 > $show}
		{section name=i start=($currPage+1) loop=($currPage+1+$show)}
		<li class="row">
			<a href="javascript:pagination('{$PAGINATION.action}', {$PAGINATION.currPage}, {$smarty.section.i.index}, '{$PAGINATION.callback}');">{$smarty.section.i.index}</a>
		</li>
		{/section}
	{else}
		{section name=i start=($currPage+1) loop=($maxPage+1)}
		<li class="row">
			<a href="javascript:pagination('{$PAGINATION.action}', {$PAGINATION.currPage}, {$smarty.section.i.index}, '{$PAGINATION.callback}');">{$smarty.section.i.index}</a>
		</li>
		{/section}
	{/if}

	{if $currPage < $maxPage}
		<li class="row">
			<a href="javascript:pagination('{$PAGINATION.action}', {$PAGINATION.currPage}, {($currPage+1)}, '{$PAGINATION.callback}');">»</a>
		</li>
		{if $maxPage - $currPage > $show}
		<li class="row">
			<a href="javascript:pagination('{$PAGINATION.action}', {$PAGINATION.currPage}, {$maxPage}, '{$PAGINATION.callback}');">Последняя</a>
		</li>
		{/if}
	{/if}
	</ul>
{/if}