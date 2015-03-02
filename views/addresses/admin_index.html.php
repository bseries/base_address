<?php

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('addresses')
	]
]);

?>
<article
	class="use-index-table"
	data-endpoint-sort="<?= $this->url([
		'action' => 'index',
		'page' => $paginator->getPages()->current,
		'orderField' => '__ORDER_FIELD__',
		'orderDirection' => '__ORDER_DIRECTION__'
	]) ?>"
>

	<div class="top-actions">
		<?= $this->html->link($t('new address'), ['action' => 'add'], ['class' => 'button add']) ?>
	</div>

	<div class="help">
		<?= $t("Addresses can be owned by a user but addresses without an owner are possible, too.") ?>
	</div>

	<?php if ($data->count()): ?>
	<table>
		<thead>
			<tr>
				<td data-sort="user.name" class="user table-sort"><?= $t('User') ?>
				<td data-sort="locality|address_line_1|recipient|organization" class="emphasize address table-sort"><?= $t('Address') ?>
				<td data-sort="modified" class="date table-sort desc"><?= $t('Modified') ?>
				<td class="actions">
		</thead>
		<tbody class="list">
			<?php foreach ($data as $item): ?>
				<?php $user = $item->user() ?>
			<tr>
				<td class="user">
				<?php if ($user): ?>
					<?= $this->html->link($user->title(), [
						'controller' => $user->isVirtual() ? 'VirtualUsers' : 'Users',
						'action' => 'edit', 'id' => $user->id,
						'library' => 'base_core'
					]) ?>
				<?php else: ?>
					-
				<?php endif ?>
				<td class="emphasize address"><?= $item->format('oneline') ?>
				<td class="date">
					<time datetime="<?= $this->date->format($item->modified, 'w3c') ?>">
						<?= $this->date->format($item->modified, 'date') ?>
					</time>
				<td class="actions">
					<?= $this->html->link($t('delete'), ['id' => $item->id, 'action' => 'delete', 'library' => 'base_address'], ['class' => 'button delete']) ?>
					<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'base_address'], ['class' => 'button']) ?>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>

	<?=$this->view()->render(['element' => 'paging'], compact('paginator'), ['library' => 'base_core']) ?>
</article>