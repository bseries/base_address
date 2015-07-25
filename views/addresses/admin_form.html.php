<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'base_address', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'single',
		'title' => false,
		'empty' => false,
		'object' => $t('address')
	]
]);

?>
<article>
	<?=$this->form->create($item) ?>
		<?= $this->form->field('id', ['type' => 'hidden']) ?>

		<div class="grid-row grid-row-last">
			<section class="grid-column-left">
				<?= $this->form->field('recipient', [
					'type' => 'text',
					'label' => $t('Recipient')
				]) ?>
				<?= $this->form->field('organization', [
					'type' => 'text',
					'label' => $t('Organization')
				]) ?>
			</section>

			<section class="grid-column-right">
				<?= $this->form->field('user_id', [
					'type' => 'select',
					'label' => $t('User'),
					'list' => $users
				]) ?>
			</section>
		</div>

		<div class="grid-row grid-row-last">
			<section class="grid-column-left">
			</section>

			<section class="grid-column-right">
				<?= $this->form->field('phone', [
					'type' => 'phone',
					'label' => $t('Phone')
				]) ?>
			</section>
		</div>


		<div class="grid-row grid-row-last">
			<section class="grid-column-left">
				<?= $this->form->field('address_line_1', [
					'type' => 'text',
					'label' => $t('Address Line')
				]) ?>
				<?= $this->form->field('address_line_2', [
					'type' => 'text',
					'label' => $t('Address Line (additional)')
				]) ?>
				<?= $this->form->field('locality', [
					'type' => 'text',
					'label' => $t('Locality')
				]) ?>
				<?= $this->form->field('dependent_locality', [
					'type' => 'text',
					'label' => $t('Dependent Locality')
				]) ?>
				<?= $this->form->field('postal_code', [
					'type' => 'text',
					'label' => $t('Postal Code')
				]) ?>
				<?= $this->form->field('sorting_code', [
					'type' => 'text',
					'label' => $t('Sorting Code')
				]) ?>
				<?= $this->form->field('administrative_area', [
					'type' => 'text',
					'label' => $t('Administrative Area')
				]) ?>
				<?= $this->form->field('country', [
					'type' => 'select',
					'label' => $t('Country'),
					'list' => $countries
				]) ?>
			</section>

			<section class="grid-column-right">
				<?= $this->form->field('address', [
					'type' => 'textarea',
					'label' => $t('Rendered Postal'),
					'disabled' => true,
					'value' => $item->format('postal', $locale)
				]) ?>
			</section>
		</div>
		<div class="bottom-actions">
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'large save']) ?>
		</div>
	<?=$this->form->end() ?>
</article>