<?php

namespace app\components;

use Yii;
use app\models\Csvs;
use yii\base\Component;
use yii\db\QueryBuilder;
use yii\web\UploadedFile;

/**
 * Class CsvParser
 * @package app\components
 *
 * @property UploadedFile file;
 */
class CsvParser extends Component
{
    /**
     * @var UploadedFile
     */
    protected $file = NULL;
    protected $hendler = NULL;
    protected $csvContent = [];
    protected $fields = [];

    public function __construct(array $config = [])
    {
        foreach ($config as $cf => $cv) {
            $this->$cf = $cv;
        }
        if (empty($this->file)) {
            throw new \Exception('No file setted!');
        }
        if ($this->file->type != 'text/csv') {
            throw new \Exception('File not a csv!');
        }
        if ($this->file->size / (1024 * 2) > 1) {
            throw new \Exception('File is more then 1mb!');
        }
        parent::__construct([]);
        $this->readFile()->analize();
    }

    /**
     * Uploads file to parse
     * @return self;
     * @throws \Exception
     */
    public static function loadFile() : self {
        $file = UploadedFile::getInstanceByName('csvu');

        if (empty($file)) {
            throw new \Exception("File not uploaded!");
        }
        return new self([
            'file' => $file,
        ]);
    }

    /**
     * @return $this
     */
    protected function readFile() {
        $this->hendler = fopen($this->file->tempName, "r");
        while ($row = fgetcsv($this->hendler, 0,',')) {
            $this->csvContent[] = $row;
        }
        return $this;
    }

    protected function analize() {
        foreach ($this->csvContent as $row) {
            foreach ($row as $key => $item) {
                $len = strlen($item);
                if (empty($this->fields['field' . $key])) {
                    $this->fields['field' . $key] = $len;
                } else {
                    if ($len > $this->fields['field' . $key]) {
                        $this->fields['field' . $key] = $len;
                    }
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function store() {
        if (empty($this->csvContent)) {
            throw new \Exception("File is empty or delimiter is not ','!");
        }
        if (empty($this->fields)) {
            throw new \Exception("Failed to parse fields!");
        }
        if (Yii::$app->user->isGuest) {
            throw new \Exception("Guests not allowed to store files!");
        }
        $csvf = new Csvs();
        $csvf->attributes = [
            'uid' => Yii::$app->user->id,
            'file_name' => $this->file->name,
            'fields' => json_encode($this->fields),
        ];
        if (!$csvf->validate()) {
            throw new \Exception('Failed to validate file link');
        }
        if (!$csvf->save()) {
            throw new \Exception('Failed to store file link');
        }

        $tableName = 'csvs_content_'.$csvf->id;
        $sql = Yii::$app->db->queryBuilder->createTable($tableName, $this->createTableParams());
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();


        foreach ($this->csvContent as $index => $row) {
            $params = [];
            $sql = Yii::$app->db->queryBuilder->insert($tableName, $this->createRow($row),$params);
            $command = Yii::$app->db->createCommand($sql);
            $command->bindValues($params);
            $command->execute();
        }
    }

    protected function createTableParams() {
        $params = [];
        $params['id'] = 'pk';
        foreach ($this->fields as $f => $v) {
            $params[$f] = "varchar({$v})";
        }
        return $params;
    }

    protected function createRow($row) {
        $params = [];
        $index = 0;
        foreach ($this->fields as $fw => $v) {
            $params[$fw] = $row[$index];
            $index++;
        }
        return $params;
    }

}