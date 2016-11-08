<?php
  2: /**
  3:  * CakeValidationRule.
  4:  *
  5:  * Provides the Model validation logic.
  6:  *
  7:  * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
  8:  * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
  9:  *
 10:  * Licensed under The MIT License
 11:  * For full copyright and license information, please see the LICENSE.txt
 12:  * Redistributions of files must retain the above copyright notice.
 13:  *
 14:  * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 15:  * @link          http://cakephp.org CakePHP(tm) Project
 16:  * @package       Cake.Model.Validator
 17:  * @since         CakePHP(tm) v 2.2.0
 18:  * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 19:  */
 20: 
 21: App::uses('Validation', 'Utility');
 22: 
 23: /**
 24:  * CakeValidationRule object. Represents a validation method, error message and
 25:  * rules for applying such method to a field.
 26:  *
 27:  * @package       Cake.Model.Validator
 28:  * @link          http://book.cakephp.org/2.0/en/data-validation.html
 29:  */
 30: class CakeValidationRule {
 31: 
 32: /**
 33:  * Whether the field passed this validation rule
 34:  *
 35:  * @var mixed
 36:  */
 37:     protected $_valid = true;
 38: 
 39: /**
 40:  * Holds whether the record being validated exists in datasource or not
 41:  *
 42:  * @var boolean
 43:  */
 44:     protected $_recordExists = false;
 45: 
 46: /**
 47:  * Validation method
 48:  *
 49:  * @var mixed
 50:  */
 51:     protected $_rule = null;
 52: 
 53: /**
 54:  * Validation method arguments
 55:  *
 56:  * @var array
 57:  */
 58:     protected $_ruleParams = array();
 59: 
 60: /**
 61:  * Holds passed in options
 62:  *
 63:  * @var array
 64:  */
 65:     protected $_passedOptions = array();
 66: 
 67: /**
 68:  * The 'rule' key
 69:  *
 70:  * @var mixed
 71:  */
 72:     public $rule = 'blank';
 73: 
 74: /**
 75:  * The 'required' key
 76:  *
 77:  * @var mixed
 78:  */
 79:     public $required = null;
 80: 
 81: /**
 82:  * The 'allowEmpty' key
 83:  *
 84:  * @var boolean
 85:  */
 86:     public $allowEmpty = null;
 87: 
 88: /**
 89:  * The 'on' key
 90:  *
 91:  * @var string
 92:  */
 93:     public $on = null;
 94: 
 95: /**
 96:  * The 'last' key
 97:  *
 98:  * @var boolean
 99:  */
100:     public $last = true;
101: 
102: /**
103:  * The 'message' key
104:  *
105:  * @var string
106:  */
107:     public $message = null;
108: 
109: /**
110:  * Constructor
111:  *
112:  * @param array $validator [optional] The validator properties
113:  */
114:     public function __construct($validator = array()) {
115:         $this->_addValidatorProps($validator);
116:     }
117: 
118: /**
119:  * Checks if the rule is valid
120:  *
121:  * @return boolean
122:  */
123:     public function isValid() {
124:         if (!$this->_valid || (is_string($this->_valid) && !empty($this->_valid))) {
125:             return false;
126:         }
127: 
128:         return true;
129:     }
130: 
131: /**
132:  * Returns whether the field can be left blank according to this rule
133:  *
134:  * @return boolean
135:  */
136:     public function isEmptyAllowed() {
137:         return $this->skip() || $this->allowEmpty === true;
138:     }
139: 
140: /**
141:  * Checks if the field is required according to the `required` property
142:  *
143:  * @return boolean
144:  */
145:     public function isRequired() {
146:         if (in_array($this->required, array('create', 'update'), true)) {
147:             if ($this->required === 'create' && !$this->isUpdate() || $this->required === 'update' && $this->isUpdate()) {
148:                 return true;
149:             }
150:             return false;
151:         }
152: 
153:         return $this->required;
154:     }
155: 
156: /**
157:  * Checks whether the field failed the `field should be present` validation
158:  *
159:  * @param string $field Field name
160:  * @param array $data Data to check rule against
161:  * @return boolean
162:  */
163:     public function checkRequired($field, &$data) {
164:         return (
165:             (!array_key_exists($field, $data) && $this->isRequired() === true) ||
166:             (
167:                 array_key_exists($field, $data) && (empty($data[$field]) &&
168:                 !is_numeric($data[$field])) && $this->allowEmpty === false
169:             )
170:         );
171:     }
172: 
173: /**
174:  * Checks if the allowEmpty key applies
175:  *
176:  * @param string $field Field name
177:  * @param array $data data to check rule against
178:  * @return boolean
179:  */
180:     public function checkEmpty($field, &$data) {
181:         if (empty($data[$field]) && $data[$field] != '0' && $this->allowEmpty === true) {
182:             return true;
183:         }
184:         return false;
185:     }
186: 
187: /**
188:  * Checks if the validation rule should be skipped
189:  *
190:  * @return boolean True if the ValidationRule can be skipped
191:  */
192:     public function skip() {
193:         if (!empty($this->on)) {
194:             if ($this->on === 'create' && $this->isUpdate() || $this->on === 'update' && !$this->isUpdate()) {
195:                 return true;
196:             }
197:         }
198:         return false;
199:     }
200: 
201: /**
202:  * Returns whether this rule should break validation process for associated field
203:  * after it fails
204:  *
205:  * @return boolean
206:  */
207:     public function isLast() {
208:         return (bool)$this->last;
209:     }
210: 
211: /**
212:  * Gets the validation error message
213:  *
214:  * @return string
215:  */
216:     public function getValidationResult() {
217:         return $this->_valid;
218:     }
219: 
220: /**
221:  * Gets an array with the rule properties
222:  *
223:  * @return array
224:  */
225:     protected function _getPropertiesArray() {
226:         $rule = $this->rule;
227:         if (!is_string($rule)) {
228:             unset($rule[0]);
229:         }
230:         return array(
231:             'rule' => $rule,
232:             'required' => $this->required,
233:             'allowEmpty' => $this->allowEmpty,
234:             'on' => $this->on,
235:             'last' => $this->last,
236:             'message' => $this->message
237:         );
238:     }
239: 
240: /**
241:  * Sets the recordExists configuration value for this rule,
242:  * ir refers to whether the model record it is validating exists
243:  * exists in the collection or not (create or update operation)
244:  *
245:  * If called with no parameters it will return whether this rule
246:  * is configured for update operations or not.
247:  *
248:  * @param boolean $exists Boolean to indicate if records exists
249:  * @return boolean
250:  */
251:     public function isUpdate($exists = null) {
252:         if ($exists === null) {
253:             return $this->_recordExists;
254:         }
255:         return $this->_recordExists = $exists;
256:     }
257: 
258: /**
259:  * Dispatches the validation rule to the given validator method
260:  *
261:  * @param string $field Field name
262:  * @param array $data Data array
263:  * @param array $methods Methods list
264:  * @return boolean True if the rule could be dispatched, false otherwise
265:  */
266:     public function process($field, &$data, &$methods) {
267:         $this->_valid = true;
268:         $this->_parseRule($field, $data);
269: 
270:         $validator = $this->_getPropertiesArray();
271:         $rule = strtolower($this->_rule);
272:         if (isset($methods[$rule])) {
273:             $this->_ruleParams[] = array_merge($validator, $this->_passedOptions);
274:             $this->_ruleParams[0] = array($field => $this->_ruleParams[0]);
275:             $this->_valid = call_user_func_array($methods[$rule], $this->_ruleParams);
276:         } elseif (class_exists('Validation') && method_exists('Validation', $this->_rule)) {
277:             $this->_valid = call_user_func_array(array('Validation', $this->_rule), $this->_ruleParams);
278:         } elseif (is_string($validator['rule'])) {
279:             $this->_valid = preg_match($this->_rule, $data[$field]);
280:         } else {
281:             trigger_error(__d('cake_dev', 'Could not find validation handler %s for %s', $this->_rule, $field), E_USER_WARNING);
282:             return false;
283:         }
284: 
285:         return true;
286:     }
287: 
288: /**
289:  * Resets internal state for this rule, by default it will become valid
290:  * and it will set isUpdate() to false
291:  *
292:  * @return void
293:  */
294:     public function reset() {
295:         $this->_valid = true;
296:         $this->_recordExists = false;
297:     }
298: 
299: /**
300:  * Returns passed options for this rule
301:  *
302:  * @param string|integer $key Array index
303:  * @return array
304:  */
305:     public function getOptions($key) {
306:         if (!isset($this->_passedOptions[$key])) {
307:             return null;
308:         }
309:         return $this->_passedOptions[$key];
310:     }
311: 
312: /**
313:  * Sets the rule properties from the rule entry in validate
314:  *
315:  * @param array $validator [optional]
316:  * @return void
317:  */
318:     protected function _addValidatorProps($validator = array()) {
319:         if (!is_array($validator)) {
320:             $validator = array('rule' => $validator);
321:         }
322:         foreach ($validator as $key => $value) {
323:             if (isset($value) || !empty($value)) {
324:                 if (in_array($key, array('rule', 'required', 'allowEmpty', 'on', 'message', 'last'))) {
325:                     $this->{$key} = $validator[$key];
326:                 } else {
327:                     $this->_passedOptions[$key] = $value;
328:                 }
329:             }
330:         }
331:     }
332: 
333: /**
334:  * Parses the rule and sets the rule and ruleParams
335:  *
336:  * @param string $field Field name
337:  * @param array $data Data array
338:  * @return void
339:  */
340:     protected function _parseRule($field, &$data) {
341:         if (is_array($this->rule)) {
342:             $this->_rule = $this->rule[0];
343:             $this->_ruleParams = array_merge(array($data[$field]), array_values(array_slice($this->rule, 1)));
344:         } else {
345:             $this->_rule = $this->rule;
346:             $this->_ruleParams = array($data[$field]);
347:         }
348:     }
349: 
350: }
