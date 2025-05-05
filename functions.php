<?php
/**
 * ACFのカスタムバリデーション
 *
 * @package acf-custom-validation
 */

/**
 * 締切日（deadline）フィールドのバリデーション
 * 現在日時より未来の日付であることを確認する
 */
add_filter(
	'acf/validate_value/name=deadline', // バリデーションを適用するフィールド名を指定
	function ( $valid, $value ) {
		// 既にバリデーションが失敗している場合は、その結果をそのまま返す
		if ( ! $valid ) {
			return $valid;
		}

		// 入力された日付が現在より未来の日付であるかをチェック
		return new DateTime() < new DateTime( $value )
			? $valid
			: '締切日は現在より未来の日付を選択してください。';
	},
	10,
	4
);

/**
 * 終了日（end_date）フィールドのバリデーション
 * 開始日より後の日付であることを確認する
 */
add_filter(
	'acf/validate_value/name=end_date',
	function ( $valid, $value ) {
		// 既にバリデーションが失敗している場合は、その結果をそのまま返す
		if ( ! $valid ) {
			return $valid;
		}

		// POSTデータから開始日を取得
		$post_id          = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$start_date_field = get_field_object( 'start_date', $post_id );
		$start_date_key   = $start_date_field['key'] ?? null;
		$start_date       = isset( $_POST['acf'][ $start_date_key ] ) ? sanitize_text_field( wp_unslash( $_POST['acf'][ $start_date_key ] ) ) : null;

		// 開始日が存在し、かつ終了日が開始日より後の日付であるかをチェック
		return $start_date && new DateTime( $start_date ) < new DateTime( $value )
			? $valid
			: '終了日は開始日より後の日付を選択してください。';
	},
	10,
	4
);
