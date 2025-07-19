    <style>
     
    </style>
</head>
<body>
    <div class="calc">
        <div class="calc__header">
            <h1 class="calc__title"><i class="fas fa-calculator"></i> <?php _e('calculator_index_title'); ?></h1>
            <p class="calc__subtitle"><?php _e('calculator_index_subtitle'); ?></p>
        </div>
        <div class="calc__body">
            <form id="calculator-form" class="calc__form">
                <div class="calc__group">
                    <label for="current-weight" class="calc__label"><i class="fas fa-weight"></i> <?php _e('calculator_index_current_weight'); ?></label>
                    <div class="calc__input-group">
                        <input type="number" id="current-weight" min="30" max="200" step="0.1" value="85" required class="calc__input">
                        <span class="calc__unit"><?php _e('calculator_index_kg'); ?></span>
                    </div>
                    <div class="calc__slider-container">
                        <input type="range" min="40" max="150" value="85" class="calc__slider" id="current-weight-slider">
                        <div class="calc__slider-labels">
                            <span>40 <?php _e('calculator_index_kg'); ?></span>
                            <span>95 <?php _e('calculator_index_kg'); ?></span>
                            <span>150 <?php _e('calculator_index_kg'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="calc__group">
                    <label for="target-weight" class="calc__label"><i class="fas fa-bullseye"></i> <?php _e('calculator_index_target_weight'); ?></label>
                    <div class="calc__input-group">
                        <input type="number" id="target-weight" min="30" max="200" step="0.1" value="70" required class="calc__input">
                        <span class="calc__unit"><?php _e('calculator_index_kg'); ?></span>
                    </div>
                    <div class="calc__slider-container">
                        <input type="range" min="40" max="150" value="70" class="calc__slider" id="target-weight-slider">
                        <div class="calc__slider-labels">
                            <span>40 <?php _e('calculator_index_kg'); ?></span>
                            <span>95 <?php _e('calculator_index_kg'); ?></span>
                            <span>150 <?php _e('calculator_index_kg'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="calc__group">
                    <label for="weight-loss-rate" class="calc__label"><i class="fas fa-tachometer-alt"></i> <?php _e('calculator_index_loss_rate'); ?></label>
                    <div class="calc__input-group">
                        <select id="weight-loss-rate" required class="calc__select">
                            <option value="0.2">0.2 <?php _e('calculator_index_kg_week'); ?> (<?php _e('calculator_index_very_slow'); ?>)</option>
                            <option value="0.4">0.4 <?php _e('calculator_index_kg_week'); ?> (<?php _e('calculator_index_slow'); ?>)</option>
                            <option value="0.6" selected>0.6 <?php _e('calculator_index_kg_week'); ?> (<?php _e('calculator_index_moderate'); ?>)</option>
                            <option value="0.8">0.8 <?php _e('calculator_index_kg_week'); ?> (<?php _e('calculator_index_fast'); ?>)</option>
                            <option value="1.0">1.0 <?php _e('calculator_index_kg_week'); ?> (<?php _e('calculator_index_very_fast'); ?>)</option>
                        </select>
                    </div>
                </div>
                <div class="calc__group">
                    <label for="activity-level" class="calc__label"><i class="fas fa-running"></i> <?php _e('calculator_index_activity'); ?></label>
                    <div class="calc__input-group">
                        <select id="activity-level" required class="calc__select">
                            <option value="1.1"><?php _e('calculator_index_activity_min'); ?></option>
                            <option value="1.2"><?php _e('calculator_index_activity_low'); ?></option>
                            <option value="1.35" selected><?php _e('calculator_index_activity_moderate'); ?></option>
                            <option value="1.5"><?php _e('calculator_index_activity_high'); ?></option>
                            <option value="1.7"><?php _e('calculator_index_activity_extreme'); ?></option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="calc__btn">
                    <i class="fas fa-calculator"></i> <?php _e('calculator_index_btn_calc'); ?>
                </button>
            </form>
            <div class="calc__result" id="result-container">
                <h2 class="calc__result-title"><i class="fas fa-chart-line"></i> <?php _e('calculator_index_result_title'); ?></h2>
                <div class="calc__result-value" id="result-value">12 недель</div>
                <div class="calc__result-message" id="result-message"></div>
            </div>
            <div class="calc__disclaimer">
                <h3 class="calc__disclaimer-title"><i class="fas fa-exclamation-triangle"></i> <?php _e('calculator_index_important'); ?></h3>
                <p class="calc__disclaimer-text"><?php _e('calculator_index_disclaimer'); ?></p>
            </div>
        </div>
    </div>

    <script>
        const CALC_LANG = "<?= app()->get('lang')['code'] ?>";
        const CALC_MSGS = {
            ru: {
                goal_achieved: "Цель достигнута!",
                goal_achieved_html: `<div class=\"calc__message--success\"><p><i class=\"fas fa-check-circle\"></i> Вы уже достигли своего целевого веса! Поздравляем!</p><p class=\"mt-2\">Рекомендуем поддерживать текущий вес и продолжать здоровый образ жизни.</p></div>`,
                error: "Ошибка",
                error_rate_html: `<div class=\"calc__message--danger\"><p><i class=\"fas fa-exclamation-circle\"></i> Скорость похудения должна быть положительным числом.</p><p class=\"mt-2\">Пожалуйста, выберите значение больше 0.</p></div>`,
                warning: "Внимание!",
                warning_rate_html: `<div class=\"calc__message--warning\"><p><i class=\"fas fa-exclamation-triangle\"></i> Вы выбрали высокую скорость похудения (",
                warning_rate_html2: " кг/неделю).</p><p class=\"mt-2\">Потеря веса более 1 кг в неделю может быть опасной для здоровья и привести к потере мышечной массы вместо жира.</p></div>`,
                error_weight_html: `<div class=\"calc__message--danger\"><p><i class=\"fas fa-exclamation-circle\"></i> Ваш текущий вес слишком низкий для похудения.</p><p class=\"mt-2\">Пожалуйста, проконсультируйтесь с врачом перед началом любой диеты.</p></div>`,
                invalid_goal: "Недопустимая цель",
                invalid_goal_html: `<div class=\"calc__message--danger\"><p><i class=\"fas fa-exclamation-circle\"></i> Выбранный целевой вес слишком низкий.</p><p class=\"mt-2\">Для большинства взрослых людей вес ниже 40 кг считается опасным для здоровья.</p></div>`,
                long_process: "Длительный процесс",
                long_process_html: `<div class=\"calc__message--warning\"><p><i class=\"fas fa-hourglass-half\"></i> При выбранной скорости похудения достижение цели займет очень много времени.</p><p class=\"mt-2\">Рекомендуем увеличить физическую активность и пересмотреть питание для ускорения процесса.</p></div>`,
            },
            en: {
                goal_achieved: "Goal achieved!",
                goal_achieved_html: `<div class=\"calc__message--success\"><p><i class=\"fas fa-check-circle\"></i> You have already reached your target weight! Congratulations!</p><p class=\"mt-2\">We recommend maintaining your current weight and continuing a healthy lifestyle.</p></div>`,
                error: "Error",
                error_rate_html: `<div class=\"calc__message--danger\"><p><i class=\"fas fa-exclamation-circle\"></i> The weight loss rate must be a positive number.</p><p class=\"mt-2\">Please select a value greater than 0.</p></div>`,
                warning: "Warning!",
                warning_rate_html: `<div class=\"calc__message--warning\"><p><i class=\"fas fa-exclamation-triangle\"></i> You have chosen a high weight loss rate (",
                warning_rate_html2: " kg/week).</p><p class=\"mt-2\">Losing more than 1 kg per week can be dangerous and may result in muscle loss instead of fat.</p></div>`,
                error_weight_html: `<div class=\"calc__message--danger\"><p><i class=\"fas fa-exclamation-circle\"></i> Your current weight is too low for weight loss.</p><p class=\"mt-2\">Please consult a doctor before starting any diet.</p></div>`,
                invalid_goal: "Invalid goal",
                invalid_goal_html: `<div class=\"calc__message--danger\"><p><i class=\"fas fa-exclamation-circle\"></i> The selected target weight is too low.</p><p class=\"mt-2\">For most adults, a weight below 40 kg is considered dangerous to health.</p></div>`,
                long_process: "Long process",
                long_process_html: `<div class=\"calc__message--warning\"><p><i class=\"fas fa-hourglass-half\"></i> At the selected weight loss rate, achieving the goal will take a very long time.</p><p class=\"mt-2\">We recommend increasing physical activity and reviewing your diet to speed up the process.</p></div>`,
            }
        };
        document.addEventListener('DOMContentLoaded', function() {
            const currentWeightInput = document.getElementById('current-weight');
            const currentWeightSlider = document.getElementById('current-weight-slider');
            const targetWeightInput = document.getElementById('target-weight');
            const targetWeightSlider = document.getElementById('target-weight-slider');
            currentWeightSlider.addEventListener('input', function() {
                currentWeightInput.value = this.value;
            });
            targetWeightSlider.addEventListener('input', function() {
                targetWeightInput.value = this.value;
            });
            currentWeightInput.addEventListener('input', function() {
                currentWeightSlider.value = this.value;
            });
            targetWeightInput.addEventListener('input', function() {
                targetWeightSlider.value = this.value;
            });
            document.getElementById('calculator-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const currentWeight = parseFloat(currentWeightInput.value);
                const targetWeight = parseFloat(targetWeightInput.value);
                const lossRate = parseFloat(document.getElementById('weight-loss-rate').value);
                const activityLevel = parseFloat(document.getElementById('activity-level').value);
                const result = calculateWeightLoss(currentWeight, targetWeight, lossRate, activityLevel);
                document.getElementById('result-value').textContent = result.value;
                document.getElementById('result-message').innerHTML = result.message;
                document.getElementById('result-container').classList.add('calc__result--active');
                document.getElementById('result-container').scrollIntoView({ behavior: 'smooth' });
            });
            const initialResult = calculateWeightLoss(85, 70, 0.6, 1.35);
            document.getElementById('result-value').textContent = initialResult.value;
            document.getElementById('result-message').innerHTML = initialResult.message;
            document.getElementById('result-container').classList.add('calc__result--active');
        });
        const CALC_MSG = {
            goal_date: "<?= addslashes(__('calculator_index_goal_date')) ?>",
            advice: "<?= addslashes(__('calculator_index_advice')) ?>",
            weeks: "<?= addslashes(__('calculator_index_weeks')) ?>",
            less_than_week: "<?= addslashes(__('calculator_index_less_than_week')) ?>",
            fast_warning: "<?= addslashes(__('calculator_index_fast_warning')) ?>",
            good_result: "<?= addslashes(__('calculator_index_good_result')) ?>",
            keep_plan: "<?= addslashes(__('calculator_index_keep_plan')) ?>",
        };
        function calculateWeightLoss(current, target, rate, activity) {
            const lang = CALC_LANG in CALC_MSGS ? CALC_LANG : 'ru';
            const msg = CALC_MSGS[lang];
            const weightDiff = current - target;
            if (weightDiff <= 0) {
                return {
                    value: msg.goal_achieved,
                    message: msg.goal_achieved_html
                };
            }
            if (rate <= 0) {
                return {
                    value: msg.error,
                    message: msg.error_rate_html
                };
            }
            if (rate > 1.0) {
                return {
                    value: msg.warning,
                    message: msg.warning_rate_html + rate + (lang === 'ru' ? ' кг/неделю' : ' kg/week') + msg.warning_rate_html2
                };
            }
            if (current < 40) {
                return {
                    value: msg.error,
                    message: msg.error_weight_html
                };
            }
            if (target < 40) {
                return {
                    value: msg.invalid_goal,
                    message: msg.invalid_goal_html
                };
            }
            if (weightDiff > 50 && rate < 0.4) {
                return {
                    value: msg.long_process,
                    message: msg.long_process_html
                };
            }
            
            let weeks = weightDiff / (rate * activity);
            
            weeks = Math.round(weeks * 10) / 10;
            
            const today = new Date();
            const targetDate = new Date(today);
            targetDate.setDate(targetDate.getDate() + weeks * 7);
            
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = targetDate.toLocaleDateString('ru-RU', options);
            
            let resultValue = `${weeks} недель`;
            let resultMessage = '';
            
            if (weeks < 1) {
                resultValue = "Менее 1 недели";
                resultMessage = `<div class="calc__message--warning">
                    <p><i class="fas fa-exclamation-triangle"></i> Вы достигнете цели очень быстро!</p>
                    <p class="mt-2">Быстрая потеря веса может быть вредна для здоровья. Убедитесь, что вы теряете не более 1 кг в неделю.</p>
                </div>`;
            } else if (weeks <= 4) {
                resultMessage = `<div class="calc__message--success">
                    <p><i class="fas fa-check-circle"></i> Отличный результат! Вы достигнете цели примерно к ${formattedDate}.</p>
                    <p class="mt-2">Придерживайтесь вашего плана питания и тренировок для достижения лучших результатов.</p>
                </div>`;
            } else if (weeks <= 12) {
                resultMessage = `<div class="calc__message--success">
                    <p><i class="fas fa-check-circle"></i> Хороший темп! Вы достигнете цели примерно к ${formattedDate}.</p>
                    <p class="mt-2">Рекомендуем вести дневник питания и регулярно отслеживать прогресс.</p>
                </div>`;
            } else if (weeks <= 26) {
                resultMessage = `<div class="calc__message--info">
                    <p><i class="fas fa-info-circle"></i> Потребуется около полугода. Вы достигнете цели примерно к ${formattedDate}.</p>
                    <p class="mt-2">Похудение в умеренном темпе более эффективно для долгосрочного поддержания веса.</p>
                </div>`;
            } else if (weeks <= 52) {
                resultMessage = `<div class="calc__message--info">
                    <p><i class="fas fa-info-circle"></i> Потребуется около года. Вы достигнете цели примерно к ${formattedDate}.</p>
                    <p class="mt-2">Не забывайте о регулярных чек-поинтах и корректировке плана при необходимости.</p>
                </div>`;
            } else {
                resultMessage = `<div class="calc__message--warning">
                    <p><i class="fas fa-hourglass-end"></i> Похудение займет более года (до ${formattedDate}).</p>
                    <p class="mt-2">Рекомендуем увеличить скорость похудения или пересмотреть целевой вес. Для консультации обратитесь к диетологу.</p>
                </div>`;
            }
            
            return {
                value: resultValue,
                message: resultMessage
            };
        }
    </script>
</body>
</html>