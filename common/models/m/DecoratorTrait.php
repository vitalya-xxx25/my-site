<?php
namespace common\models\m;

/**
 * Трейт декоратор
 *
 * Назначение: заменяет AR модель расширенной моделью Model.
 * Можно создать несколько отображений/расширений AR модели и
 * использовать их для легкой замены или расширения функционала.
 *
 * Иницализация $_decorators происходит в init()
 * Пример:
 * use common\models\active_record\dict\Allocation;
 * use frontend\models\tophotels\AllocationModel;
 * public function init() {
 *     $this->_decorators = [
 *         Allocation::className() => AllocationModel::className(),
 *     ];
 * }
 *
 * Класс декоратор должен быть наследником декарируемого класса
 */
trait DecoratorTrait {
    protected $_decorators = [];

    /**
     * Декарирует класс при связке один ко многим
     * @param string $class
     * @param array $link
     */
    public function hasMany($class, $link) {
        if (array_key_exists($class, $this->_decorators) && class_exists($this->_decorators[$class])) {
            if (is_a($this->_decorators[$class], $class, true)) {
                $class = $this->_decorators[$class];
            }
            else {
                throw new \Exception('This class ' . $this->_decorators[$class] . ' not extends ' . $class);
            }
        }
        return parent::hasMany($class, $link);
    }

    /**
     * Декарирует класс при связке один ко одному
     * @param string $class
     * @param array $link
     */
    public function hasOne($class, $link) {
        if (array_key_exists($class, $this->_decorators) && class_exists($this->_decorators[$class])) {
            if (is_a($this->_decorators[$class], $class, true)) {
                $class = $this->_decorators[$class];
            }
            else {
                throw new \Exception('This class ' . $this->_decorators[$class] . ' not extends ' . $class);
            }
        }
        return parent::hasOne($class, $link);
    }

}