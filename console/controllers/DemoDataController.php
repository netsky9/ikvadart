<?php
namespace console\controllers;

use common\models\Manufacturer;
use common\models\Spare;
use common\models\SpareGroup;
use common\models\SpareGroupSpare;
use common\models\User;
use yii\console\Controller;

class DemoDataController extends Controller
{
	public function actionIndex()
	{
		$manufacturesList = [
			'Lmi', 'Boge', 'Behr'
		];

		$spareGroupsList = [
			'Acura', 'Audi', 'BMW', 'Cadillac', 'Chery', 'Chevrolet', 'Citroen'
		];

		$sparesList = [
			[
				'name' => 'Тормозные колодки',
				'weight' => 20,
				'manufacturer_id' => 1,
				'cost' => 1500,
			],
			[
				'name' => 'Приводные ремни и патрубки',
				'weight' => 30,
				'manufacturer_id' => 1,
				'cost' => 100,
			],
			[
				'name' => 'Фильтры',
				'weight' => 50,
				'manufacturer_id' => 1,
				'cost' => 1800,
			],
			[
				'name' => 'Амортизаторы',
				'weight' => 10,
				'manufacturer_id' => 1,
				'cost' => 100,
			],
			[
				'name' => 'Резинометаллические детали подвески',
				'weight' => 50,
				'manufacturer_id' => 1,
				'cost' => 1100,
			],
			[
				'name' => 'Сцепление',
				'weight' => 120,
				'manufacturer_id' => 2,
				'cost' => 100,
			],
			[
				'name' => 'Подшипники',
				'weight' => 220,
				'manufacturer_id' => 2,
				'cost' => 1200,
			],
			[
				'name' => 'Стекла',
				'weight' => 1020,
				'manufacturer_id' => 2,
				'cost' => 700,
			],
			[
				'name' => 'Оптика',
				'weight' => 720,
				'manufacturer_id' => 1,
				'cost' => 100,
			],
			[
				'name' => 'Свечи зажигания',
				'weight' => 240,
				'manufacturer_id' => 3,
				'cost' => 200,
			],
			[
				'name' => 'Провода зажигания',
				'weight' => 220,
				'manufacturer_id' => 1,
				'cost' => 100,
			],
			[
				'name' => 'Зажигание',
				'weight' => 920,
				'manufacturer_id' => 1,
				'cost' => 1000,
			],
		];

		$spareGroupsSparesList = [
			[1, 1],
			[2, 1],
			[3, 1],
			[3, 2],
			[3, 3],
			[3, 4],
			[5, 4],
			[6, 4],
			[7, 4],
			[1, 5],
			[2, 5],
			[2, 6],
			[3, 6],
			[3, 7],
			[3, 8],
			[3, 9],
			[3, 10],
			[3, 11],
			[4, 12],
		];

		foreach ($manufacturesList as $manufactureItem){
			$manufacturer = new Manufacturer();
			$manufacturer->name = $manufactureItem;
			$manufacturer->save();
		}

		foreach ($spareGroupsList as $spareGroupsItem){
			$spareGroup = new SpareGroup();
			$spareGroup->name = $spareGroupsItem;
			$spareGroup->save();
		}

		foreach ($sparesList as $spareItem){
			$spare = new Spare();
			$spare->name = $spareItem['name'];
			$spare->weight = $spareItem['weight'];
			$spare->manufacturer_id = $spareItem['manufacturer_id'];
			$spare->cost = $spareItem['cost'];
			$spare->save();
		}

		foreach ($spareGroupsSparesList as $spareGroupsSparesItem){
			$spareGroupsSpare = new SpareGroupSpare();
			$spareGroupsSpare->spare_group_id = $spareGroupsSparesItem[0];
			$spareGroupsSpare->spare_id = $spareGroupsSparesItem[1];
			$spareGroupsSpare->save();
		}

		echo "***********\n";
		echo "Done\n";
		echo "***********\n";

	}
}
