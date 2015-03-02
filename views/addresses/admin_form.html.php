<?php

$this->set([
	'page' => [
		'type' => 'single',
		'title' => false,
		'empty' => false,
		'object' => $t('address')
	]
]);

?>
<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?>">
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
				<div class="compound-users">
					<?php
						$user = $item->exists() ? $item->user() : false;
					?>
					<?= $this->form->field('user_id', [
						'type' => 'select',
						'label' => $t('User'),
						'list' => $users,
						'class' => !$user || !$user->isVirtual() ? null : 'hide'
					]) ?>
					<?= $this->form->field('virtual_user_id', [
						'type' => 'select',
						'label' => false,
						'list' => $virtualUsers,
						'class' => $user && $user->isVirtual() ? null : 'hide'
					]) ?>
					<?= $this->form->field('user.is_real', [
						'type' => 'checkbox',
						'label' => $t('real user'),
						'checked' => $user ? !$user->isVirtual() : true
					]) ?>
				</div>
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
			</section>
		</div>
		<div class="bottom-actions">
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'large save']) ?>
		</div>
	<?=$this->form->end() ?>
</article>