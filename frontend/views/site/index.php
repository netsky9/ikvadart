<?php

use yii\widgets\ActiveForm;

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-3" style="background: #f4f4f4;">
                <h2>Filter</h2>
				<? $form = ActiveForm::begin([
					'method' => 'get',
					'action' => ['site/index']
				]); ?>
					<div class="form-group">
						<label for="manufacturer">Manufacture</label>
						<select class="form-control" id="manufacturer" name="manufacturer">
							<? foreach ($manufacturers as $manufacturer): ?>
								<option value="<?=$manufacturer['id']?>"
									<?= isset($_GET['manufacturer']) && ($manufacturer['id'] == $_GET['manufacturer']) ? 'selected' : '' ?>
								>
									<?=$manufacturer['name']?>
								</option>
							<? endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col">
								<label for="weightFrom">Weight from</label>
								<input type="text" id="weightFrom" name="weightFrom" class="form-control" value="<?= $_GET['weightFrom'] ?? '' ?>">
							</div>
							<div class="col">
								<label for="weightTo">Weight to</label>
								<input type="text" id="weightTo" name="weightTo" class="form-control" value="<?= $_GET['weightTo'] ?? '' ?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="spareGroups">Spare groups</label>
						<select multiple class="form-control" id="spareGroups" name="spareGroups[]">
							<? foreach ($spareGroups as $spareGroup): ?>
								<option value="<?=$spareGroup['id']?>"
									<?= isset($_GET['spareGroups']) && (in_array($spareGroup['id'], $_GET['spareGroups'])) ? 'selected' : '' ?>
								>
									<?=$spareGroup['name']?>
								</option>
							<? endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col">
								<label for="costFrom">Cost from</label>
								<input type="text" id="costFrom" name="costFrom" class="form-control" value="<?= $_GET['costFrom'] ?? '' ?>">
							</div>
							<div class="col">
								<label for="costTo">Cost to</label>
								<input type="text" id="costTo" name="costTo" class="form-control" value="<?= $_GET['costTo'] ?? '' ?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">Filter</button>
						<a href="<?= yii\helpers\Url::to('index.php?r=site')?>">Clear</a>
					</div>
				<? ActiveForm::end(); ?>
            </div>
            <div class="col-lg-9">
                <h2>Spares</h2>
				<div class="row">
					<? foreach ($spares as $spare): ?>
					<div class="col-lg-4">
						<div class="card" style="margin-bottom: 30px">
							<div class="card-body">
								<h4><b><?=$spare['name']?></b></h4>
								<span class="badge badge-dark">Weight: <?=$spare['weight']?></span>
								<span class="badge badge-warning">Cost: <?=$spare['cost']?></span>
								<span class="badge badge-light">Spare groups: </span>

								<? foreach (explode(',', $spare['spare_group_names']) as $spareGroupName): ?>
									<span class="badge badge-light"><?=$spareGroupName?></span>
								<? endforeach; ?>

							</div>
						</div>
					</div>
					<? endforeach; ?>
				</div>
				<?= \yii\widgets\LinkPager::widget([
					'pagination' => $pages,
				]); ?>
            </div>
        </div>

    </div>
</div>
