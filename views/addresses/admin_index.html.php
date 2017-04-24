<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'base_address', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('addresses')
	]
]);

?>
<article
	class="use-rich-index"
	data-endpoint="<?= $this->url([
		'action' => 'index',
		'page' => '__PAGE__',
		'orderField' => '__ORDER_FIELD__',
		'orderDirection' => '__ORDER_DIRECTION__',
		'filter' => '__FILTER__'
	]) ?>"
>

	<div class="top-actions">
		<?= $this->html->link($t('address'), ['action' => 'add'], ['class' => 'button add']) ?>
	</div>

	<?php if ($data->count()): ?>
	<table>
		<thead>
			<tr>
				<td data-sort="User.name" class="user table-sort"><?= $t('User') ?>
				<td data-sort="locality|address_line_1|recipient|organization" class="emphasize address table-sort"><?= $t('Address') ?>
				<td data-sort="modified" class="date table-sort desc"><?= $t('Modified') ?>
				<td class="actions">
					<?= $this->form->field('search', [
						'type' => 'search',
						'label' => false,
						'placeholder' => $t('Filter'),
						'class' => 'table-search',
						'value' => $this->_request->filter
					]) ?>
		</thead>
		<tbody>
			<?php foreach ($data as $item): ?>
			<tr>
				<td class="user">
					<?= $this->user->link($item->user()) ?>
				<td class="emphasize address"><?= $item->format('compact') ?>
				<td class="date">
					<time datetime="<?= $this->date->format($item->modified, 'w3c') ?>">
						<?= $this->date->format($item->modified, 'date') ?>
					</time>
				<td class="actions">
					<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'base_address'], ['class' => 'button']) ?>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>

	<?=$this->_render('element', 'paging', compact('paginator'), ['library' => 'base_core']) ?>

	<div class="bottom-help">
		<?= $t("Addresses can be owned by a user but addresses without an owner are possible, too.") ?>
	</div>

</article>