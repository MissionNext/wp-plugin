<?php
/**
 * @var Array $mainFields
 * @var Array $secondaryFields
 * @var Array $defaults
 * @var String $mainRole
 * @var String $secondaryRole
 */

$mainRoleLabel = ucfirst($mainRole) . ' fields';
$secondaryRoleLabel = ( 'organization' == $secondaryRole ? 'Receiving Organization' : 'Job' ) . ' fields';

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/model/matching_config', 'model/matching_config.js', array( 'jquery' ), false, true);
?>

<form method="POST" action="<?php echo $_SERVER['REQUEST_URI'] ?>">

    <table class="relations wp-list-table widefat" >
        <thead>
            <tr>
                <th><?php echo $mainRoleLabel ?></th>
                <th><?php echo $secondaryRoleLabel ?></th>
                <th>Weight</th>
                <th>Relation type</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($defaults as $key=> $row): ?>
            <tr>
                <td>
                    <input type="hidden" name="mn_rels[<?php echo $key ?>][main_field_id]" value="<?php echo $row['main_field']['id'] ?>"/>
                    <span><?php echo $row['main_field']['name'] ?></span>
                </td>
                <td>
                    <input type="hidden" name="mn_rels[<?php echo $key ?>][matching_field_id]" value="<?php echo $row['matching_field']['id'] ?>"/>
                    <span><?php echo $row['matching_field']['name'] ?></span>
                </td>
                <td>
                    <input type="hidden" name="mn_rels[<?php echo $key ?>][weight]" value="<?php echo $row['weight'] ?>"/>
                    <span><?php echo \MissionNext\lib\Constants::$matchingWeights[$row['weight']] ?></span>
                </td>
                <td>
                    <input type="hidden" name="mn_rels[<?php echo $key ?>][matching_type]" value="<?php echo $row['matching_type'] ?>"/>
                    <span><?php echo \MissionNext\lib\Constants::$matchingTypes[$row['matching_type']] ?></span>
                </td>
                <td>
                    <button type="button" class="delete button">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <select id="mainFields">
                        <?php foreach($mainFields as $mainField): ?>
                            <option value="<?php echo $mainField['id'] ?>"><?php echo $mainField['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select id="secondaryFields">
                        <?php foreach($secondaryFields as $secondaryField): ?>
                            <option value="<?php echo $secondaryField['id'] ?>"><?php echo $secondaryField['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select id="weights">
                        <?php foreach(\MissionNext\lib\Constants::$matchingWeights as $key => $label): ?>
                        <option value="<?php echo $key ?>"><?php echo $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select id="relationType">
                        <?php foreach(\MissionNext\lib\Constants::$matchingTypes as $key => $label): ?>
                            <option value="<?php echo $key ?>"><?php echo $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <button type="button" class="button" id="relationAdd"><?php echo 'Add' ?></button>
                </td>
            </tr>
        </tfoot>
    </table>

    <button type="submit" class="button button-primary"><?php echo 'Save' ?></button>

</form>